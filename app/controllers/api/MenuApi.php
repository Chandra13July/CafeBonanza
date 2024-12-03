<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../core/Database.php';

class MenuApi
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllMenus()
    {
        $this->db->query("SELECT * FROM menu");
        $menus = $this->db->resultSet();

        $data = ["data" => []];
        foreach ($menus as $menu) {
            $data_menu = [
                "MenuId" => $menu["MenuId"],
                "MenuName" => $menu["MenuName"],
                "Description" => $menu["Description"],
                "Price" => $menu["Price"],
                "Stock" => $menu["Stock"],
                "Category" => $menu["Category"],
                "ImageUrl" => $menu["ImageUrl"], // URL sudah disimpan penuh di DB
                "CreatedAt" => $menu["CreatedAt"],
            ];

            array_push($data['data'], $data_menu);
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function getMenuById($id)
    {
        $this->db->query("SELECT * FROM menu WHERE MenuId = :id");
        $this->db->bind(':id', $id);
        $menu = $this->db->single();

        header('Content-Type: application/json');
        echo json_encode($menu);
    }

    public function addMenu()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['imageUrl'])) {
            http_response_code(400);
            echo json_encode(["message" => "Invalid request"]);
            return;
        }

        $menuName = $_POST['MenuName'] ?? '';
        $description = $_POST['Description'] ?? '';
        $price = $_POST['Price'] ?? null;
        $stock = $_POST['Stock'] ?? null;
        $category = $_POST['Category'] ?? '';

        $price = $price ?? 0;
        $stock = $stock ?? 0;

        if (!is_numeric($price) || !is_numeric($stock)) {
            http_response_code(400);
            echo json_encode(["message" => "Price and Stock must be numeric."]);
            return;
        }

        if (empty($menuName) || empty($description) || empty($category)) {
            http_response_code(400);
            echo json_encode(["message" => "Required fields are missing."]);
            return;
        }

        $uploadedImagePath = $this->uploadImage();

        if ($uploadedImagePath === false) {
            http_response_code(500);
            echo json_encode(["message" => "Failed to upload image"]);
            return;
        }

        $this->db->query("INSERT INTO menu (MenuName, Description, Price, Stock, Category, ImageUrl) 
                          VALUES (:name, :description, :price, :stock, :category, :imageUrl)");
        $this->db->bind(':name', $menuName);
        $this->db->bind(':description', $description);
        $this->db->bind(':price', (int)$price);
        $this->db->bind(':stock', (int)$stock);
        $this->db->bind(':category', $category);
        $this->db->bind(':imageUrl', $uploadedImagePath);

        try {
            $this->db->execute();
            http_response_code(201);
            echo json_encode(["message" => "Menu added successfully"]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Database error: " . $e->getMessage()]);
        }
    }

    private function uploadImage()
    {
        if (isset($_FILES['imageUrl']) && $_FILES['imageUrl']['error'] === 0) {
            $imageName = $_FILES['imageUrl']['name'];
            $imageTmpName = $_FILES['imageUrl']['tmp_name'];
            $imageSize = $_FILES['imageUrl']['size'];
            $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageExt, $allowed)) {
                if ($imageSize < 5000000) {
                    $newImageName = uniqid('', true) . '.' . $imageExt;
                    $uploadDir = __DIR__ . '/../../public/upload/menu/'; 
                    $relativePath = 'upload/menu/' . $newImageName; 
                    $imageUploadPath = $uploadDir . $newImageName;

                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                            error_log('Gagal membuat folder: ' . $uploadDir);
                            return false;
                        }
                    }

                    if (move_uploaded_file($imageTmpName, $imageUploadPath)) {
                        return $relativePath;
                    } else {
                        error_log('File upload failed: ' . print_r(error_get_last(), true));
                        return false;
                    }
                } else {
                    error_log('File size exceeds the limit.');
                    return false; 
                }
            } else {
                error_log('File type not allowed: ' . $imageExt);
                return false;
            }
        }
        return null;
    }

    public function updateMenu($id)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $input = json_decode(file_get_contents('php://input'), true);

        if ($id === null || empty($input)) {
            http_response_code(400);
            echo json_encode(['message' => 'No ID or data provided']);
            return;
        }

        $updateFields = [];
        $bindParams = [];
        $allowedFields = ['MenuName', 'Price', 'Stock', 'Description', 'Category'];

        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                $updateFields[] = "$field = :$field";
                $bindParams[":$field"] = $input[$field];
            }
        }

        if (isset($_FILES['imageUrl']) && $_FILES['imageUrl']['error'] === 0) {
            $uploadedImagePath = $this->uploadImage();

            if ($uploadedImagePath === false) {
                http_response_code(500);
                echo json_encode(["message" => "Failed to upload image"]);
                return;
            }

            $updateFields[] = "ImageUrl = :imageUrl";
            $bindParams[':imageUrl'] = $uploadedImagePath;
        }

        if (empty($updateFields)) {
            http_response_code(400);
            echo json_encode(['message' => 'No valid fields to update']);
            return;
        }

        $query = "UPDATE menu SET " . implode(', ', $updateFields) . " WHERE MenuId = :id";
        $bindParams[':id'] = $id;

        $this->db->query($query);

        foreach ($bindParams as $key => $value) {
            $this->db->bind($key, $value);
        }

        try {
            if ($this->db->execute()) {
                http_response_code(200);
                echo json_encode(["message" => "Menu updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Failed to update menu"]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Database error: " . $e->getMessage()]);
        }
    }

    public function deleteMenu($id)
    {
        $this->db->query("DELETE FROM menu WHERE MenuId = :id");
        $this->db->bind(':id', $id);

        try {
            if ($this->db->execute()) {
                http_response_code(200);
                echo json_encode(["message" => "Menu deleted successfully"]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Menu not found"]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Failed to delete menu: " . $e->getMessage()]);
        }
    }
}

try {
    $menuApi = new MenuApi();
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    header('Content-Type: application/json');

    switch ($method) {
        case 'GET':
            if ($id) $menuApi->getMenuById($id);
            else $menuApi->getAllMenus();
            break;
        case 'POST':
            $menuApi->addMenu();
            break;
        case 'PUT':
        case 'PATCH':
            if ($id) $menuApi->updateMenu($id);
            else http_response_code(400);
            break;
        case 'DELETE':
            if ($id) $menuApi->deleteMenu($id);
            else http_response_code(400);
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
