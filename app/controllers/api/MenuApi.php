<?php

require_once __DIR__ . '/../../config/config.php'; // Memuat konfigurasi aplikasi
require_once __DIR__ . '/../../core/Database.php'; // Memuat class Database

class MenuApi
{
    private $db;

    public function __construct()
    {
        $this->db = new Database(); // Membuat instance dari Database
    }

    /**
     * Mengambil semua menu dari database
     * dan mengembalikannya dalam format JSON.
     */
    public function getAllMenus()
    {
        $this->db->query("SELECT * FROM menu"); // Query untuk mendapatkan semua menu
        $menus = $this->db->resultSet(); // Menyimpan hasil query

        $data = ["data" => []]; // Array untuk menyimpan data menu
        foreach ($menus as $menus) {
            $data_menu = [
                "MenuId" => $menus["MenuId"],
                "MenuName" => $menus["MenuName"],
                "Description" => $menus["Description"],
                "Price" => $menus["Price"],
                "Stock" => $menus["Stock"],
                "Category" => $menus["Category"],
                "ImageUrl" => BASEURL . '/' . $menus["ImageUrl"], // URL lengkap gambar
                "CreatedAt" => $menus["CreatedAt"],
            ];

            array_push($data['data'], $data_menu); // Menambahkan data menu ke array data
        }

        echo json_encode($data); // Mengembalikan data dalam format JSON
    }

    /**
     * Mengambil menu berdasarkan ID tertentu.
     */
    public function getMenuById($id)
    {
        $this->db->query("SELECT * FROM menu WHERE MenuId = :id"); // Query untuk mengambil menu berdasarkan ID
        $this->db->bind(':id', $id); // Binding parameter ID
        $menu = $this->db->single(); // Mendapatkan satu data menu

        echo json_encode($menu); // Mengembalikan data menu dalam format JSON
    }

    /**
     * Menambahkan menu baru ke database.
     */
    public function addMenu($data)
    {
        $uploadedImagePath = $this->uploadImage(); // Mengunggah gambar dan mendapatkan path-nya

        if ($uploadedImagePath === false) {
            echo json_encode(["message" => "Failed to upload image"]); // Jika gagal upload, kirim pesan error
            return;
        }

        $data['ImageUrl'] = $uploadedImagePath ?? null; // Menambahkan URL gambar ke data menu

        // Query untuk menambahkan menu ke database
        $this->db->query("INSERT INTO menu (MenuName, Description, Price, Stock, Category, ImageUrl) 
                      VALUES (:name, :description, :price, :stock, :category, :imageUrl)");
        $this->db->bind(':name', $data['MenuName']);
        $this->db->bind(':description', $data['Description']);
        $this->db->bind(':price', $data['Price']);
        $this->db->bind(':stock', $data['Stock']);
        $this->db->bind(':category', $data['Category']);
        $this->db->bind(':imageUrl', $data['ImageUrl']);

        // Menjalankan query dan mengembalikan pesan berdasarkan hasilnya
        if ($this->db->execute()) {
            echo json_encode(["message" => "Menu added successfully"]);
        } else {
            echo json_encode(["message" => "Failed to add menu"]);
        }
    }

    /**
     * Mengunggah gambar menu ke direktori server.
     */
    private function uploadImage()
    {
        if (isset($_FILES['imageUrl']) && $_FILES['imageUrl']['error'] === 0) {
            $imageName = $_FILES['imageUrl']['name']; // Nama file gambar
            $imageTmpName = $_FILES['imageUrl']['tmp_name']; // Path sementara file gambar
            $imageSize = $_FILES['imageUrl']['size']; // Ukuran file gambar
            $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION)); // Ekstensi file gambar

            $allowed = ['jpg', 'jpeg', 'png', 'gif']; // Jenis file yang diperbolehkan

            if (in_array($imageExt, $allowed)) { // Periksa apakah file termasuk jenis yang diperbolehkan
                if ($imageSize < 5000000) { // Periksa apakah ukuran file di bawah 5MB
                    $newImageName = uniqid('', true) . '.' . $imageExt; // Nama baru file gambar
                    $imageUploadPath = 'upload/menu/' . $newImageName; // Path untuk mengunggah file

                    // Buat direktori jika belum ada
                    if (!is_dir('upload/menu/')) {
                        mkdir('upload/menu/', 0755, true);
                    }

                    // Pindahkan file gambar ke direktori tujuan
                    if (move_uploaded_file($imageTmpName, $imageUploadPath)) {
                        return $imageUploadPath; // Kembalikan path file yang diunggah
                    } else {
                        $_SESSION['error'] = "Gagal mengunggah gambar.";
                        return false; // Gagal mengunggah
                    }
                } else {
                    $_SESSION['error'] = "Ukuran gambar terlalu besar.";
                    return false; // Ukuran file terlalu besar
                }
            } else {
                $_SESSION['error'] = "Jenis file gambar tidak valid.";
                return false; // Jenis file tidak valid
            }
        }
        return null; // Tidak ada gambar yang diunggah
    }

    /**
     * Memperbarui menu berdasarkan ID tertentu.
     */
    public function updateMenu($id, $data)
    {
        if (empty($data)) { // Periksa jika data kosong
            echo json_encode(["message" => "No data received"]);
            return;
        }

        // Query untuk memperbarui data menu
        $this->db->query("UPDATE menu SET MenuName = :name, Description = :description, Price = :price, 
                          Stock = :stock, Category = :category, ImageUrl = :imageUrl WHERE MenuId = :id");

        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['MenuName']);
        $this->db->bind(':description', $data['Description']);
        $this->db->bind(':price', $data['Price']);
        $this->db->bind(':stock', $data['Stock']);
        $this->db->bind(':category', $data['Category']);
        $this->db->bind(':imageUrl', $data['ImageUrl']);

        // Jalankan query dan kirim pesan berdasarkan hasil
        if ($this->db->execute()) {
            echo json_encode(["message" => "Menu updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update menu"]);
        }
    }

    /**
     * Menghapus menu berdasarkan ID.
     */
    public function deleteMenu($id)
    {
        $this->db->query("DELETE FROM menu WHERE MenuId = :id"); // Query untuk menghapus menu
        $this->db->bind(':id', $id); // Binding parameter ID

        // Jalankan query dan kirim pesan berdasarkan hasil
        if ($this->db->execute()) {
            echo json_encode(["message" => "Menu deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete menu"]);
        }
    }
}

$menuApi = new MenuApi();

header("Content-Type: application/json"); // Menyetel header untuk JSON

// Menangani permintaan HTTP
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $menuApi->getMenuById($_GET['id']); // Ambil menu berdasarkan ID
    } else {
        $menuApi->getAllMenus(); // Ambil semua menu
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'MenuName' => $_POST['MenuName'] ?? '',
        'Description' => $_POST['Description'] ?? '',
        'Price' => $_POST['Price'] ?? '',
        'Stock' => $_POST['Stock'] ?? '',
        'Category' => $_POST['Category'] ?? ''
    ];

    $menuApi->addMenu($data); // Tambah menu
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        $menuApi->deleteMenu($_GET['id']); // Hapus menu berdasarkan ID
    }
} else {
    echo json_encode(["message" => "Invalid request"]); // Tanggapan untuk permintaan tidak valid
}
?>
