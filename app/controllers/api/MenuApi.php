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
                "ImageUrl" => BASEURL . '/' . $menu["ImageUrl"],
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

        if ($price === null || $stock === null || !is_numeric($price) || !is_numeric($stock)) {
            http_response_code(400);
            echo json_encode(["message" => "Price and Stock must be numeric."]);
            return;
        }

        $price = (int) $price;
        $stock = (int) $stock;

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
        $this->db->bind(':price', $price);
        $this->db->bind(':stock', $stock);
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
        // Periksa apakah file diunggah tanpa error
        if (isset($_FILES['imageUrl']) && $_FILES['imageUrl']['error'] === 0) {
            $imageName = $_FILES['imageUrl']['name'];
            $imageTmpName = $_FILES['imageUrl']['tmp_name'];
            $imageSize = $_FILES['imageUrl']['size'];
            $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            // Format file yang diizinkan
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageExt, $allowed)) {
                // Periksa ukuran file (maks 5MB)
                if ($imageSize < 5000000) {
                    $newImageName = uniqid('', true) . '.' . $imageExt; // Nama file unik

                    // Menentukan lokasi folder public/upload/menu/
                    $baseDir = dirname(__DIR__, 3); // Tiga tingkat ke atas untuk mencapai root proyek
                    $uploadDir = $baseDir . '/public/upload/menu/'; // Lokasi penyimpanan absolut
                    $relativePath = 'upload/menu/' . $newImageName; // Path relatif untuk disimpan di database
                    $imageUploadPath = $uploadDir . $newImageName;

                    // Cek dan buat folder jika belum ada
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                            error_log('Gagal membuat folder: ' . $uploadDir);
                            return false;
                        }
                    }

                    // Pindahkan file ke folder target
                    if (move_uploaded_file($imageTmpName, $imageUploadPath)) {
                        // Return path relatif untuk disimpan di database
                        return $relativePath;
                    } else {
                        // Debug jika terjadi kesalahan saat memindahkan file
                        error_log('File upload failed: ' . print_r(error_get_last(), true));
                        return false;
                    }
                } else {
                    error_log('File size exceeds the limit.');
                    return false; // Ukuran file terlalu besar
                }
            } else {
                error_log('File type not allowed: ' . $imageExt);
                return false; // Format file tidak diizinkan
            }
        }
        return null; // Tidak ada file yang diunggah
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

    public function getStockStatus($threshold = 5)
    {
        // Fixing the SQL query by removing the trailing comma
        $this->db->query("
        SELECT 
            m.MenuId,
            m.MenuName,
            m.Stock,
            m.ImageUrl
        FROM menu m
        WHERE m.Stock = 0  -- Menampilkan menu dengan stok habis
        OR m.Stock <= :threshold  -- Atau stok hampir habis
        ORDER BY m.MenuName
    ");

        // Bind the threshold value to the SQL query
        $this->db->bind(':threshold', $threshold);

        // Execute the query and get the results
        $menus = $this->db->resultSet();

        // Prepare the response data
        $data = ["data" => []];
        foreach ($menus as $menu) {
            $data_menu = [
                "MenuId" => $menu["MenuId"],
                "MenuName" => $menu["MenuName"],
                "Stock" => $menu["Stock"],
                "ImageUrl" => BASEURL . '/' . $menu["ImageUrl"]
            ];

            // Add the menu item to the response data
            array_push($data['data'], $data_menu);
        }

        // Return the data as a JSON response
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function deleteMenu($id)
    {
        $this->db->query("DELETE FROM menu WHERE MenuId = :id");
        $this->db->bind(':id', $id);

        try {
            $result = $this->db->execute();

            if ($result) {
                http_response_code(200);
                echo json_encode(["message" => "Menu deleted successfully"]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Menu not found or could not be deleted"]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["message" => "Failed to delete menu: " . $e->getMessage()]);
        }
    }
}

// Error handling for undefined routes
function handleError($message)
{
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(["message" => $message]);
    exit;
}

// Main routing logic
// Main routing logic
try {
    // Create instance of MenuApi
    $menuApi = new MenuApi();

    // Determine request method
    $method = $_SERVER['REQUEST_METHOD'];

    // Extract ID or action from URL if present
    $id = $_GET['id'] ?? null;
    $action = $_GET['action'] ?? null; // Check for 'action' parameter in the URL

    // Route the request
    header('Content-Type: application/json');

    switch ($method) {
        case 'GET':
            if ($action == 'stockstatus') {
                $threshold = $_GET['threshold'] ?? 5;  // Get threshold from URL or default to 5
                $menuApi->getStockStatus($threshold);  // Call the stock status function
            } elseif ($id) {
                $menuApi->getMenuById($id);
            } else {
                $menuApi->getAllMenus();
            }
            break;

        case 'POST':
            $menuApi->addMenu();
            break;

        case 'PUT':
        case 'PATCH':
            if ($id) {
                $menuApi->updateMenu($id);
            } else {
                handleError('No ID provided for update');
            }
            break;

        case 'DELETE':
            if ($id) {
                $menuApi->deleteMenu($id);
            } else {
                handleError('No ID provided for deletion');
            }
            break;

        default:
            handleError('Invalid request method');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server error: " . $e->getMessage()]);
}
