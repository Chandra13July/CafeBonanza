<?php

class Cart extends Controller
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = $this->model('CartModel');
    }

    public function index()
    {
        $this->view('layout/header');
        $this->view('home/cart');
    }

    public function btnAddCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_SESSION['user_id'])) {
                $customerId = intval($_SESSION['user_id']);
                $menuId = intval($_POST['menu_id']);
                $quantity = intval($_POST['quantity']);

                if ($quantity <= 0) {
                    $_SESSION['error'] = "Jumlah harus lebih dari 0!";
                    header('Location: ' . BASEURL . '/home/menu');
                    exit();
                }

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
?>
