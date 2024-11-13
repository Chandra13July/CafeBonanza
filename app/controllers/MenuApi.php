<?php

// Mengimpor file konfigurasi dan koneksi database
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';

class MenuApi
{
    private $db;

    public function __construct()
    {
        // Membuat instance database
        $this->db = new Database();
    }

    // Mendapatkan semua data menu
    public function getAllMenus()
    {
        $this->db->query("SELECT * FROM menu");
        $menus = $this->db->resultSet();

        echo json_encode(["data"=>$menus]);
    }

    // Mendapatkan data menu berdasarkan ID
    public function getMenuById($id)
    {
        $this->db->query("SELECT * FROM menu WHERE MenuId = :id");
        $this->db->bind(':id', $id);
        $menu = $this->db->single();

        echo json_encode($menu);
    }

    // Menambahkan data menu baru
    public function addMenu($data)
    {
        $this->db->query("INSERT INTO menu (MenuName, Description, Price, Stock, Category, ImageUrl) 
                          VALUES (:name, :description, :price, :stock, :category, :imageUrl)");
        $this->db->bind(':name', $data['MenuName']);
        $this->db->bind(':description', $data['Description']);
        $this->db->bind(':price', $data['Price']);
        $this->db->bind(':stock', $data['Stock']);
        $this->db->bind(':category', $data['Category']);
        $this->db->bind(':imageUrl', $data['ImageUrl']);
        
        if ($this->db->execute()) {
            echo json_encode(["message" => "Menu added successfully"]);
        } else {
            echo json_encode(["message" => "Failed to add menu"]);
        }
    }

    // Mengedit data menu berdasarkan ID
    public function updateMenu($id, $data)
    {
        $this->db->query("UPDATE menu SET MenuName = :name, Description = :description, Price = :price, 
                          Stock = :stock, Category = :category, ImageUrl = :imageUrl WHERE MenuId = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['MenuName']);
        $this->db->bind(':description', $data['Description']);
        $this->db->bind(':price', $data['Price']);
        $this->db->bind(':stock', $data['Stock']);
        $this->db->bind(':category', $data['Category']);
        $this->db->bind(':imageUrl', $data['ImageUrl']);

        if ($this->db->execute()) {
            echo json_encode(["message" => "Menu updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update menu"]);
        }
    }

    // Menghapus data menu berdasarkan ID
    public function deleteMenu($id)
    {
        $this->db->query("DELETE FROM menu WHERE MenuId = :id");
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            echo json_encode(["message" => "Menu deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete menu"]);
        }
    }
}

// Menggunakan API dengan metode HTTP
$menuApi = new MenuApi();

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $menuApi->getMenuById($_GET['id']);
    } else {
        $menuApi->getAllMenus();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $menuApi->addMenu($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($_GET['id'])) {
        $menuApi->updateMenu($_GET['id'], $data);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        $menuApi->deleteMenu($_GET['id']);
    }
} else {
    echo json_encode(["message" => "Invalid request"]);
}
