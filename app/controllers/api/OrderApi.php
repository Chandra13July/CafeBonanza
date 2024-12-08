<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../core/Database.php';

class OrderApi
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function checkoutOrder()
    {
        // Ambil data dari request
        $input = json_decode(file_get_contents("php://input"), true);

        // Validasi data yang diperlukan
        if (
            empty($input['Total']) ||
            empty($input['Paid']) ||
            empty($input['OrderDetails']) ||
            !is_array($input['OrderDetails']) // Pastikan OrderDetails adalah array
        ) {
            http_response_code(400);
            echo json_encode(["message" => "Missing required fields"]);
            return;
        }

        // Set PaymentMethod default menjadi "Cash" jika tidak ada dalam request
        $paymentMethod = isset($input['PaymentMethod']) ? $input['PaymentMethod'] : 'Cash';

        // Hitung kembalian
        $change = $input['Paid'] - $input['Total'];

        // Mulai transaksi database
        $this->db->beginTransaction();

        try {
            // Insert data ke tabel `orders`
            $this->db->query("INSERT INTO `order` 
        (CustomerId, Total, Paid, `Change`, PaymentMethod, Status, CreatedAt) 
        VALUES (1, :total, :paid, :change, :paymentMethod, :status, NOW())");

            // Bind parameter
            $this->db->bind(':total', $input['Total']);
            $this->db->bind(':paid', $input['Paid']);
            $this->db->bind(':change', $change);
            $this->db->bind(':paymentMethod', $paymentMethod);  // Gunakan PaymentMethod yang sudah diset default
            $this->db->bind(':status', 'Completed'); // Status default "Completed"

            // Eksekusi query dan dapatkan OrderId yang baru saja disisipkan
            $this->db->execute();
            $orderId = $this->db->lastInsertId();

            // Insert OrderDetails
            $orderDetails = [];
            foreach ($input['OrderDetails'] as $detail) {
                // Hitung Subtotal
                $subtotal = $detail['Quantity'] * $detail['Price'];

                $this->db->query("INSERT INTO `orderdetail` (OrderId, MenuId, Quantity, Price, Subtotal) 
                              VALUES (:orderId, :menuId, :quantity, :price, :subtotal)");

                $this->db->bind(':orderId', $orderId);
                $this->db->bind(':menuId', $detail['MenuId']);
                $this->db->bind(':quantity', $detail['Quantity']);
                $this->db->bind(':price', $detail['Price']);
                $this->db->bind(':subtotal', $subtotal);
                $this->db->execute();

                // Menyimpan detail untuk respons
                $orderDetails[] = [
                    "MenuId" => $detail['MenuId'],
                    "Quantity" => $detail['Quantity'],
                    "Price" => $detail['Price'],
                    "Subtotal" => $subtotal
                ];
            }

            // Commit transaksi
            $this->db->commit();

            // Respons sukses dengan data pesanan
            http_response_code(201);
            echo json_encode([
                "data" => [
                    [
                        "OrderId" => $orderId,
                        "CustomerId" => 1, // Asumsi CustomerId = 1
                        "Total" => $input['Total'],
                        "Paid" => $input['Paid'],
                        "Change" => $change, // Menambahkan Change pada respons
                        "PaymentMethod" => $paymentMethod, // Kirimkan PaymentMethod yang sudah diset default
                        "Status" => 'Completed',
                        "CreatedAt" => date('Y-m-d H:i:s'),
                        "OrderDetails" => $orderDetails
                    ]
                ]
            ]);
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi error
            $this->db->rollBack();
            http_response_code(500);
            echo json_encode(["message" => "Failed to create order: " . $e->getMessage()]);
        }
    }

    // Mendapatkan semua pesanan
    public function getAllOrders()
    {
        $this->db->query("SELECT * FROM `order`");
        $orders = $this->db->resultSet();

        $data = ["data" => []];

        foreach ($orders as $order) {
            $data_order = [
                "OrderId" => $order["OrderId"],
                "CustomerId" => $order["CustomerId"],
                "Total" => $order["Total"] ?? 0,
                "Paid" => $order["Paid"] ?? 0,
                "Change" => $order["Change"] ?? 0,
                "PaymentMethod" => $order["PaymentMethod"] ?? 'N/A',
                "Status" => $order["Status"] ?? 'Unknown',
                "CreatedAt" => $order["CreatedAt"] ?? 'Unknown'
            ];

            array_push($data['data'], $data_order);
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // Mendapatkan pesanan berdasarkan CustomerId
    public function getOrderByCustomer($customerId = 1) // Default CustomerId adalah 1
    {
        // Query ke database untuk mengambil data pesanan beserta OrderDetails
        $this->db->query("
        SELECT 
            o.OrderId, 
            o.CustomerId, 
            o.Total, 
            o.Paid, 
            o.Change, 
            o.PaymentMethod, 
            o.Status, 
            o.CreatedAt, 
            od.MenuId, 
            od.Quantity, 
            od.Price, 
            od.Subtotal
        FROM `order` o
        LEFT JOIN `orderdetail` od ON o.OrderId = od.OrderId
        WHERE o.CustomerId = :customerId
    ");
        $this->db->bind(':customerId', $customerId);
        $orders = $this->db->resultSet();

        // Cek jika tidak ada data
        if (empty($orders)) {
            http_response_code(404);
            echo json_encode(["message" => "No orders found for CustomerId: $customerId"]);
            return;
        }

        // Format data
        $data = ["data" => []];
        $currentOrderId = null;
        $orderDetails = [];

        foreach ($orders as $order) {
            // Jika OrderId berubah, berarti kita sudah selesai dengan satu pesanan, dan bisa push pesanan sebelumnya
            if ($order['OrderId'] !== $currentOrderId) {
                // Jika ada pesanan yang sudah selesai, simpan data pesanan sebelumnya
                if ($currentOrderId !== null) {
                    $data['data'][] = [
                        "OrderId" => $currentOrderId,
                        "CustomerId" => $order['CustomerId'],
                        "Total" => $order['Total'],
                        "Paid" => $order['Paid'],
                        "Change" => $order['Change'],
                        "PaymentMethod" => $order['PaymentMethod'],
                        "Status" => $order['Status'],
                        "CreatedAt" => $order['CreatedAt'],
                        "OrderDetails" => $orderDetails
                    ];
                }

                // Reset detail pesanan untuk pesanan yang baru
                $currentOrderId = $order['OrderId'];
                $orderDetails = [];

                // Menambahkan detail pesanan pertama untuk OrderId yang baru
                $orderDetails[] = [
                    "MenuId" => $order['MenuId'],
                    "Quantity" => $order['Quantity'],
                    "Price" => $order['Price'],
                    "Subtotal" => $order['Subtotal']
                ];
            } else {
                // Jika OrderId masih sama, tambahkan detail pesanan berikutnya
                $orderDetails[] = [
                    "MenuId" => $order['MenuId'],
                    "Quantity" => $order['Quantity'],
                    "Price" => $order['Price'],
                    "Subtotal" => $order['Subtotal']
                ];
            }
        }

        // Menambahkan pesanan terakhir
        if ($currentOrderId !== null) {
            $data['data'][] = [
                "OrderId" => $currentOrderId,
                "CustomerId" => $order['CustomerId'],
                "Total" => $order['Total'],
                "Paid" => $order['Paid'],
                "Change" => $order['Change'],
                "PaymentMethod" => $order['PaymentMethod'],
                "Status" => $order['Status'],
                "CreatedAt" => $order['CreatedAt'],
                "OrderDetails" => $orderDetails
            ];
        }

        // Return hasil
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

// Inisialisasi API dan metode request
try {
    $orderApi = new OrderApi();
    $method = $_SERVER['REQUEST_METHOD'];

    header('Content-Type: application/json');

    switch ($method) {
        case 'POST':
            $orderApi->checkoutOrder();
            break;

        case 'GET':
            if (isset($_GET['CustomerId'])) {
                $orderApi->getOrderByCustomer($_GET['CustomerId']);
            } else {
                $orderApi->getAllOrders();
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server error: " . $e->getMessage()]);
}
