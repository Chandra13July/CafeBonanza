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

    // Fungsi untuk mengambil semua order
    public function getOrders()
    {
        $this->db->query("SELECT * FROM orders");
        $orders = $this->db->resultSet();

        $data = ["data" => []];
        foreach ($orders as $order) {
            // Ambil detail untuk setiap order
            $this->db->query("SELECT * FROM order_details WHERE OrderId = :orderId");
            $this->db->bind(':orderId', $order['OrderId']);
            $orderDetails = $this->db->resultSet();

            $order['OrderDetails'] = $orderDetails;
            array_push($data['data'], $order);
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // Fungsi untuk mendapatkan order berdasarkan ID
    public function getOrderById($id)
    {
        $this->db->query("SELECT * FROM orders WHERE OrderId = :orderId");
        $this->db->bind(':orderId', $id);
        $order = $this->db->single();

        if ($order) {
            $this->db->query("SELECT * FROM order_details WHERE OrderId = :orderId");
            $this->db->bind(':orderId', $id);
            $orderDetails = $this->db->resultSet();
            $order['OrderDetails'] = $orderDetails;

            header('Content-Type: application/json');
            echo json_encode($order);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Order not found"]);
        }
    }

    // Fungsi untuk menambahkan order baru
    public function createOrder()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $orderDate = $input['OrderDate'] ?? date('Y-m-d H:i:s');
        $totalAmount = $input['TotalAmount'] ?? 0;
        $status = $input['Status'] ?? 'Pending';

        if (!is_numeric($totalAmount)) {
            http_response_code(400);
            echo json_encode(["message" => "TotalAmount must be numeric."]);
            return;
        }

        // Masukkan order baru ke dalam tabel orders
        $this->db->query("INSERT INTO orders (OrderDate, TotalAmount, Status) VALUES (:orderDate, :totalAmount, :status)");
        $this->db->bind(':orderDate', $orderDate);
        $this->db->bind(':totalAmount', $totalAmount);
        $this->db->bind(':status', $status);

        try {
            $this->db->execute();
            $orderId = $this->db->lastInsertId();

            http_response_code(201);
            echo json_encode(["message" => "Order created successfully", "OrderId" => $orderId]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Database error: " . $e->getMessage()]);
        }
    }

    // Fungsi untuk menambahkan item ke order
    public function addItemToOrder($orderId)
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $menuId = $input['MenuId'] ?? null;
        $quantity = $input['Quantity'] ?? 1;
        $price = $input['Price'] ?? 0;

        if (!$menuId || $quantity <= 0 || !is_numeric($price)) {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input"]);
            return;
        }

        // Cek apakah order detail sudah ada untuk menu tertentu
        $this->db->query("SELECT * FROM order_details WHERE OrderId = :orderId AND MenuId = :menuId");
        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':menuId', $menuId);
        $existingItem = $this->db->single();

        if ($existingItem) {
            // Jika item sudah ada, tambahkan quantity
            $this->db->query("UPDATE order_details SET Quantity = Quantity + :quantity WHERE OrderId = :orderId AND MenuId = :menuId");
            $this->db->bind(':quantity', $quantity);
            $this->db->bind(':orderId', $orderId);
            $this->db->bind(':menuId', $menuId);
        } else {
            // Jika item belum ada, tambahkan item baru
            $this->db->query("INSERT INTO order_details (OrderId, MenuId, Quantity, Price) VALUES (:orderId, :menuId, :quantity, :price)");
            $this->db->bind(':orderId', $orderId);
            $this->db->bind(':menuId', $menuId);
            $this->db->bind(':quantity', $quantity);
            $this->db->bind(':price', $price);
        }

        try {
            $this->db->execute();

            // Update total amount setelah item ditambahkan
            $this->db->query("SELECT SUM(Quantity * Price) AS TotalAmount FROM order_details WHERE OrderId = :orderId");
            $this->db->bind(':orderId', $orderId);
            $this->db->execute();
            $totalAmount = $this->db->single()['TotalAmount'];

            $this->db->query("UPDATE orders SET TotalAmount = :totalAmount WHERE OrderId = :orderId");
            $this->db->bind(':totalAmount', $totalAmount);
            $this->db->bind(':orderId', $orderId);
            $this->db->execute();

            http_response_code(200);
            echo json_encode(["message" => "Item added to order"]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Database error: " . $e->getMessage()]);
        }
    }

    // Fungsi untuk mengubah status order (misalnya menjadi 'Completed')
    public function updateOrderStatus($orderId)
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $status = $input['Status'] ?? null;

        if (!$status) {
            http_response_code(400);
            echo json_encode(["message" => "Status is required"]);
            return;
        }

        $this->db->query("UPDATE orders SET Status = :status WHERE OrderId = :orderId");
        $this->db->bind(':status', $status);
        $this->db->bind(':orderId', $orderId);

        try {
            $this->db->execute();
            http_response_code(200);
            echo json_encode(["message" => "Order status updated successfully"]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Database error: " . $e->getMessage()]);
        }
    }

    // Fungsi untuk menghapus order beserta detailnya
    public function deleteOrder($orderId)
    {
        // Hapus order details terlebih dahulu
        $this->db->query("DELETE FROM order_details WHERE OrderId = :orderId");
        $this->db->bind(':orderId', $orderId);
        $this->db->execute();

        // Hapus order
        $this->db->query("DELETE FROM orders WHERE OrderId = :orderId");
        $this->db->bind(':orderId', $orderId);

        try {
            $this->db->execute();
            http_response_code(200);
            echo json_encode(["message" => "Order deleted successfully"]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Failed to delete order: " . $e->getMessage()]);
        }
    }
}

try {
    $orderApi = new OrderApi();
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    header('Content-Type: application/json');

    switch ($method) {
        case 'GET':
            if ($id) {
                $orderApi->getOrderById($id);
            } else {
                $orderApi->getOrders();
            }
            break;
        case 'POST':
            $orderApi->createOrder();
            break;
        case 'PUT':
            if ($id) {
                $orderApi->updateOrderStatus($id);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Order ID is required"]);
            }
            break;
        case 'DELETE':
            if ($id) {
                $orderApi->deleteOrder($id);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "Order ID is required"]);
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
