<?php

class Cart extends Controller
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = $this->model('CartModel');

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'Anda harus login terlebih dahulu!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
        }
    }

    public function index()
    {
        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/cart');
    }

    public function btnAddCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_SESSION['user_id'])) {
                $customerId = intval($_SESSION['user_id']);
                $menuId = intval($_POST['menu_id']);
                $quantity = intval($_POST['quantity']);

                $menuModel = $this->model('MenuModel');
                $menu = $menuModel->getMenuById($menuId);

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

    public function deleteAll()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id'])) {
                $customerId = intval($_SESSION['user_id']);
                $cartModel = $this->model('CartModel');

                if ($cartModel->deleteAllItems($customerId)) {
                    $_SESSION['success'] = "Semua item berhasil dihapus dari keranjang!";
                } else {
                    $_SESSION['error'] = "Gagal menghapus item dari keranjang, silakan coba lagi.";
                }
            } else {
                $_SESSION['error'] = "Anda harus login untuk menghapus item.";
            }

            header('Location: ' . BASEURL . '/cart/index');
            exit();
        }
    }

    public function deleteItem($cartId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id'])) {
                $customerId = intval($_SESSION['user_id']);
                $cartModel = $this->model('CartModel');

                if ($cartModel->deleteItem($customerId, $cartId)) {
                    $_SESSION['success'] = "Item berhasil dihapus dari keranjang!";
                } else {
                    $_SESSION['error'] = "Gagal menghapus item dari keranjang, silakan coba lagi.";
                }
            } else {
                $_SESSION['error'] = "Anda harus login untuk menghapus item.";
            }

            header('Location: ' . BASEURL . '/cart/index');
            exit();
        }
    }
}
