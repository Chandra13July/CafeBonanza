<?php

class Menu extends Controller
{
    private $menuModel;

    public function __construct()
    {
        $this->menuModel = $this->model('MenuModel');
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'Anda harus login terlebih dahulu!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        $menu = $this->menuModel->getAllMenu();

        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/menu', ['menu' => $menu]);
    }

    public function btnAddMenu()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $imagePath = $this->uploadImage();

            if ($imagePath === false) {
                header("Location: " . BASEURL . "/menu/index");
                exit();
            }

            $data = [
                'menuName' => trim($_POST['menuName']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'stock' => trim($_POST['stock']),
                'category' => trim($_POST['category']),
                'imageUrl' => $imagePath 
            ];

            if ($this->menuModel->addMenu($data)) {
                $_SESSION['success'] = "Menu berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Penambahan menu gagal, silakan coba lagi.";
            }
            header("Location: " . BASEURL . "/menu/index");
            exit();
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
                    $imageUploadPath = 'upload/menu/' . $newImageName;

                    if (move_uploaded_file($imageTmpName, $imageUploadPath)) {
                        return $imageUploadPath;
                    } else {
                        $_SESSION['error'] = "Gagal mengunggah gambar.";
                        return false;
                    }
                } else {
                    $_SESSION['error'] = "Ukuran gambar terlalu besar.";
                    return false;
                }
            } else {
                $_SESSION['error'] = "Jenis file gambar tidak valid.";
                return false;
            }
        }
        return null; 
    }

    public function btnEditMenu()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $menu = $this->menuModel->findMenuById($_POST['MenuId']); 

            $data = [
                'MenuId' => $_POST['MenuId'], 
                'menuName' => trim($_POST['menuName']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'stock' => trim($_POST['stock']),
                'category' => trim($_POST['category']),
            ];

            if (!empty($_FILES['imageUrl']['name'])) {
                $data['imageUrl'] = $this->uploadImage(); 
            } else {
                $data['imageUrl'] = $menu['ImageUrl'];
            }

            if ($this->menuModel->editMenu($data)) {
                $_SESSION['success'] = "Menu berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Pembaharuan menu gagal.";
            }

            header("Location: " . BASEURL . "/menu/index");
            exit();
        }
    }

    public function btnDeleteMenu()
    {
        if (isset($_POST['MenuId'])) {
            $menuId = $_POST['MenuId'];

            if ($this->menuModel->deleteMenu($menuId)) {
                $_SESSION['success'] = "Menu berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus menu, silakan coba lagi.";
            }
        } else {
            $_SESSION['error'] = "Menu ID tidak valid.";
        }

        header("Location: " . BASEURL . "/menu/index");
        exit();
    }
}
