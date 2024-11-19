<?php

class Home extends Controller
{

    private $contactModel;
    private $customerModel;

    public function __construct()
    {
        $this->contactModel = $this->model('ContactModel');
        $this->customerModel = $this->model('CustomerModel');
    }

    public function index()
    {
        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/home');
        $this->view('layout/footer');
    }

    public function about()
    {
        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/about');
        $this->view('layout/footer');
    }

    public function menu($category = null)
    {
        $MenuItems = $this->model('MenuModel')->getMenu($category);

        $data = [
            'MenuItems' => $MenuItems,
            'SelectedCategory' => $category,
            'Notification' => isset($_SESSION['notification']) ? $_SESSION['notification'] : null,
        ];

        if (isset($_SESSION['notification'])) {
            unset($_SESSION['notification']);
        }

        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/menu', $data);
        $this->view('layout/footer');
    }

    public function gallery()
    {
        $galleryItems = $this->model('GalleryModel')->getAllGallery();

        $data = [
            'galleryItems' => $galleryItems
        ];

        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/gallery', $data);
        $this->view('layout/footer');
    }

    public function contact()
    {
        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/contact');
        $this->view('layout/footer');
    }

    public function btnContact()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'type' => $_POST['type'],
                'message' => trim($_POST['message']),
            ];

            if ($this->contactModel->addContact($data)) {
                $_SESSION['success'] = "Kontak berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Penambahan kontak gagal, silakan coba lagi.";
            }
            header("Location: " . BASEURL . "/home/contact");
            exit();
        }
    }

    public function profile()
    {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            $customerModel = $this->model('CustomerModel');
            $customerData = $customerModel->getUserByUsername($username);

            if ($customerData) {
                $this->view('layout/header');
                $this->view('layout/sidebarprofil');
                $this->view('home/profile', $customerData);
            } else {
                echo "Data pengguna tidak ditemukan!";
            }
        } else {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function btnAddCart()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Pastikan pengguna sudah login
            if (isset($_SESSION['user_id'])) {
                $customerId = intval($_SESSION['user_id']);
                $menuId = intval($_POST['menu_id']);
                $quantity = intval($_POST['quantity']);

                // Validasi input jumlah
                if ($quantity <= 0) {
                    $_SESSION['error'] = "Jumlah harus lebih dari 0!";
                    header('Location: ' . BASEURL . '/home/menu');
                    exit();
                }

                // Cek ketersediaan stok menu
                $menuModel = $this->model('MenuModel');
                $menu = $menuModel->getMenuById($menuId);

                if (!$menu) {
                    $_SESSION['error'] = "Menu tidak ditemukan.";
                    header('Location: ' . BASEURL . '/home/menu');
                    exit();
                }

                if ($menu['Stock'] < $quantity) {
                    $_SESSION['error'] = "Jumlah melebihi stok yang tersedia.";
                    header('Location: ' . BASEURL . '/home/menu');
                    exit();
                }

                // Tambahkan ke keranjang
                $cartModel = $this->model('CartModel');
                if ($cartModel->addToCart($customerId, $menuId, $quantity)) {
                    $_SESSION['success'] = "Item berhasil ditambahkan ke keranjang!";
                } else {
                    $_SESSION['error'] = "Gagal menambahkan item ke keranjang, silakan coba lagi.";
                }
            } else {
                $_SESSION['error'] = "Anda harus login untuk menambahkan item ke keranjang.";
            }

            header('Location: ' . BASEURL . '/home/menu');
            exit();
        }
    }
}
