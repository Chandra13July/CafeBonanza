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

    public function checkoutOrderFromCart($customerId = 1)
    {
        // Ambil data cart customer
        $this->db->query("SELECT * FROM `cart` WHERE CustomerId = :customerId");
        $this->db->bind(':customerId', $customerId);
        $cartItems = $this->db->resultSet();

        // Periksa apakah ada item di cart
        if (empty($cartItems)) {
            http_response_code(400);
            echo json_encode(["message" => "Cart is empty, no items to checkout"]);
            return;
        }

        // Ambil data order dari cart untuk checkout
        $orderDetails = [];
        $total = 0;

        foreach ($cartItems as $item) {
            // Jika Price kosong, ambil dari tabel menu
            if (empty($item['Price'])) {
                $this->db->query("SELECT Price FROM `menu` WHERE MenuId = :menuId");
                $this->db->bind(':menuId', $item['MenuId']);
                $menuItem = $this->db->single();
                $item['Price'] = $menuItem['Price']; // Set price from menu table if it's missing in the cart
            }

            // Pastikan Price tidak kosong sebelum melanjutkan
            if (empty($item['Price'])) {
                http_response_code(400);
                echo json_encode(["message" => "Price not found for MenuId: " . $item['MenuId']]);
                return;
            }

            // Hitung subtotal untuk setiap item di cart
            $subtotal = $item['Quantity'] * $item['Price'];
            $orderDetails[] = [
                "MenuId" => $item['MenuId'],
                "Quantity" => $item['Quantity'],
                "Price" => $item['Price'],
                "Subtotal" => $subtotal
            ];
            $total += $subtotal;
        }

        // Validasi jika data 'Paid' dikirimkan dalam request
        $input = json_decode(file_get_contents("php://input"), true);
        $paid = isset($input['Paid']) ? $input['Paid'] : $total; // Ambil nilai Paid dari input atau set sesuai total
        $paymentMethod = isset($input['PaymentMethod']) ? $input['PaymentMethod'] : 'Cash';

        // Hitung kembalian
        $change = $paid - $total;

        // Mulai transaksi
        $this->db->beginTransaction();

        try {
            // Insert ke tabel `order`
            $this->db->query("INSERT INTO `order` (CustomerId, Total, Paid, `Change`, PaymentMethod, Status, CreatedAt) 
                        VALUES (:customerId, :total, :paid, :change, :paymentMethod, :status, NOW())");

            // Bind parameter
            $this->db->bind(':customerId', $customerId);
            $this->db->bind(':total', $total);
            $this->db->bind(':paid', $paid);
            $this->db->bind(':change', $change);
            $this->db->bind(':paymentMethod', $paymentMethod);
            $this->db->bind(':status', 'Completed'); // Status default "Completed"

            // Eksekusi query
            $this->db->execute();
            $orderId = $this->db->lastInsertId();

            // Insert order details
            foreach ($orderDetails as $detail) {
                $this->db->query("INSERT INTO `orderdetail` (OrderId, MenuId, Quantity, Price, Subtotal) 
                              VALUES (:orderId, :menuId, :quantity, :price, :subtotal)");

                $this->db->bind(':orderId', $orderId);
                $this->db->bind(':menuId', $detail['MenuId']);
                $this->db->bind(':quantity', $detail['Quantity']);
                $this->db->bind(':price', $detail['Price']);
                $this->db->bind(':subtotal', $detail['Subtotal']);
                $this->db->execute();
            }

            // Commit transaksi
            $this->db->commit();

            // Hapus cart setelah checkout
            $this->db->query("DELETE FROM `cart` WHERE CustomerId = :customerId");
            $this->db->bind(':customerId', $customerId);
            $this->db->execute();

            // Respons sukses dengan data pesanan
            http_response_code(201);
            echo json_encode([
                "data" => [
                    [
                        "OrderId" => $orderId,
                        "CustomerId" => $customerId,
                        "Total" => $total,
                        "Paid" => $paid,
                        "Change" => $change,
                        "PaymentMethod" => $paymentMethod,
                        "Status" => 'Completed',
                        "CreatedAt" => date('Y-m-d H:i:s'),
                        "OrderDetails" => $orderDetails
                    ]
                ]
            ]);
        } catch (Exception $e) {
            // Rollback jika terjadi error
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
            $orderApi->checkoutOrderFromCart();
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
