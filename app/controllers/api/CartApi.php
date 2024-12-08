<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../core/Database.php';

class CartApi
{
    private $db;
    private $customerId = 1; // Default, bisa diganti jika diperlukan untuk otentikasi

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getCart()
    {
        try {
            $this->db->query(
                "SELECT 
                    c.CartId,
                    m.ImageUrl,
                    m.MenuName,
                    m.Description,
                    m.Price,
                    m.Stock,
                    c.Quantity,
                    (m.Price * c.Quantity) AS TotalPrice,
                    c.CreatedAt
                FROM 
                    cart c
                JOIN 
                    menu m ON c.MenuId = m.MenuId
                WHERE 
                    c.CustomerId = :CustomerId
                ORDER BY 
                    c.CreatedAt"
            );

            $this->db->bind(':CustomerId', $this->customerId);
            $cartItems = $this->db->resultSet();

            $data = ['data' => []];
            foreach ($cartItems as $item) {
                $data['data'][] = [
                    'CartId' => $item['CartId'],
                    'ImageUrl' => (defined('BASEURL') ? BASEURL : '') . '/' . $item['ImageUrl'],
                    'MenuName' => $item['MenuName'],
                    'Description' => $item['Description'],
                    'Price' => $item['Price'],
                    'Stock' => $item['Stock'],
                    'Quantity' => $item['Quantity'],
                    'TotalPrice' => $item['TotalPrice'],
                    'CreatedAt' => $item['CreatedAt'],
                ];
            }

            header('Content-Type: application/json');
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'message' => 'Error retrieving cart',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function addToCart()
    {
        // Mengambil data dari request body
        $data = json_decode(file_get_contents("php://input"));

        // Validasi input
        if (empty($data->customerId) || empty($data->menuId) || empty($data->quantity)) {
            http_response_code(400);
            echo json_encode(['message' => 'CustomerId, MenuId, and Quantity are required']);
            return;
        }

        $customerId = $data->customerId;
        $menuId = $data->menuId;
        $quantity = $data->quantity;

        try {
            // Cek apakah item sudah ada di keranjang
            $stmt = $this->db->prepare("SELECT Quantity FROM cart WHERE CustomerId = :CustomerId AND MenuId = :MenuId");
            $stmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
            $stmt->bindParam(':MenuId', $menuId, PDO::PARAM_INT);
            $stmt->execute();

            $existingCartItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingCartItem) {
                // Item sudah ada, update jumlah
                $newQuantity = $existingCartItem['Quantity'] + $quantity;

                $updateStmt = $this->db->prepare("
                    UPDATE cart 
                    SET Quantity = :Quantity 
                    WHERE CustomerId = :CustomerId AND MenuId = :MenuId
                ");
                $updateStmt->bindParam(':Quantity', $newQuantity, PDO::PARAM_INT);
                $updateStmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
                $updateStmt->bindParam(':MenuId', $menuId, PDO::PARAM_INT);
                $updateStmt->execute();

                echo json_encode(['message' => 'Cart updated successfully']);
            } else {
                // Item belum ada di keranjang, insert baru
                $insertStmt = $this->db->prepare("
                    INSERT INTO cart (CustomerId, MenuId, Quantity) 
                    VALUES (:CustomerId, :MenuId, :Quantity)
                ");
                $insertStmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
                $insertStmt->bindParam(':MenuId', $menuId, PDO::PARAM_INT);
                $insertStmt->bindParam(':Quantity', $quantity, PDO::PARAM_INT);
                $insertStmt->execute();

                echo json_encode(['message' => 'Item added to cart successfully']);
            }
        } catch (PDOException $e) {
            // Error handling
            http_response_code(500);
            error_log("Error in addToCart: " . $e->getMessage());
            echo json_encode(['message' => 'Error adding to cart', 'error' => $e->getMessage()]);
        }
    }

    public function deleteCart()
    {
        // Mengambil data dari request body
        $data = json_decode(file_get_contents("php://input"));

        // Validasi input
        if (empty($data->customerId)) {
            http_response_code(400);
            echo json_encode(['message' => 'CustomerId is required']);
            return;
        }

        $customerId = $data->customerId;

        try {
            if (!empty($data->cartId)) {
                // Hapus item tertentu berdasarkan CartId
                $cartId = $data->cartId;

                $deleteStmt = $this->db->prepare("
                DELETE FROM cart 
                WHERE CartId = :CartId AND CustomerId = :CustomerId
            ");
                $deleteStmt->bindParam(':CartId', $cartId, PDO::PARAM_INT);
                $deleteStmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
                $deleteStmt->execute();

                if ($deleteStmt->rowCount() > 0) {
                    echo json_encode(['message' => 'Item deleted successfully']);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'Cart item not found']);
                }
            } else {
                // Hapus semua item dari keranjang untuk CustomerId tertentu
                $deleteStmt = $this->db->prepare("
                DELETE FROM cart 
                WHERE CustomerId = :CustomerId
            ");
                $deleteStmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
                $deleteStmt->execute();

                if ($deleteStmt->rowCount() > 0) {
                    echo json_encode(['message' => 'All items deleted successfully']);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'No cart items found for this customer']);
                }
            }
        } catch (PDOException $e) {
            // Error handling
            http_response_code(500);
            error_log("Error in deleteCart: " . $e->getMessage());
            echo json_encode(['message' => 'Error deleting cart items', 'error' => $e->getMessage()]);
        }
    }

    public function deleteAllCart()
    {
        // Mengambil data dari request body
        $data = json_decode(file_get_contents("php://input"));

        // Validasi input
        if (empty($data->customerId)) {
            http_response_code(400);
            echo json_encode(['message' => 'CustomerId is required']);
            return;
        }

        $customerId = $data->customerId;

        try {
            // Hapus semua item dari keranjang untuk CustomerId tertentu
            $deleteStmt = $this->db->prepare("
            DELETE FROM cart 
            WHERE CustomerId = :CustomerId
        ");
            $deleteStmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
            $deleteStmt->execute();

            if ($deleteStmt->rowCount() > 0) {
                echo json_encode(['message' => 'All items deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'No cart items found for this customer']);
            }
        } catch (PDOException $e) {
            // Error handling
            http_response_code(500);
            error_log("Error in deleteAllCart: " . $e->getMessage());
            echo json_encode(['message' => 'Error deleting all cart items', 'error' => $e->getMessage()]);
        }
    }

    public function updateCart()
    {
        // Mengambil data dari request body
        $data = json_decode(file_get_contents("php://input"));

        // Validasi input
        if (empty($data->customerId) || empty($data->cartId) || !isset($data->quantityChange)) {
            http_response_code(400);
            echo json_encode(['message' => 'CustomerId, CartId, and QuantityChange are required']);
            return;
        }

        $customerId = $data->customerId;
        $cartId = $data->cartId;
        $quantityChange = $data->quantityChange; // Bisa bernilai positif atau negatif

        try {
            // Ambil item dari keranjang berdasarkan CartId dan CustomerId
            $stmt = $this->db->prepare("
            SELECT Quantity 
            FROM cart 
            WHERE CartId = :CartId AND CustomerId = :CustomerId
        ");
            $stmt->bindParam(':CartId', $cartId, PDO::PARAM_INT);
            $stmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
            $stmt->execute();

            $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cartItem) {
                // Hitung Quantity baru
                $newQuantity = $cartItem['Quantity'] + $quantityChange;

                // Pastikan Quantity tidak kurang dari 1
                if ($newQuantity < 1) {
                    http_response_code(400);
                    echo json_encode(['message' => 'Quantity cannot be less than 1']);
                    return;
                }

                // Update Quantity di database
                $updateStmt = $this->db->prepare("
                UPDATE cart 
                SET Quantity = :Quantity 
                WHERE CartId = :CartId AND CustomerId = :CustomerId
            ");
                $updateStmt->bindParam(':Quantity', $newQuantity, PDO::PARAM_INT);
                $updateStmt->bindParam(':CartId', $cartId, PDO::PARAM_INT);
                $updateStmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
                $updateStmt->execute();

                echo json_encode(['message' => 'Cart updated successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Cart item not found']);
            }
        } catch (PDOException $e) {
            // Error handling
            http_response_code(500);
            error_log("Error in updateCart: " . $e->getMessage());
            echo json_encode(['message' => 'Error updating cart', 'error' => $e->getMessage()]);
        }
    }
}

$cartApi = new CartApi();
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        $cartApi->getCart();
        break;
    case 'POST':
        $cartApi->addToCart();
        break;
    case 'PUT':
        $cartApi->updateCart();
        break;
    case 'DELETE':
        if (isset($_GET['action']) && $_GET['action'] === 'delete-all') {
            $cartApi->deleteAllCart();
        } else {
            $cartApi->deleteCart();
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
}
