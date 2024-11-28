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
        $totalCartItems = $this->getItemCountCart();

        $data = [
            'totalCartItems' => $totalCartItems
        ];

        $this->view('layout/header');
        $this->view('layout/navbar', $data);
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

    public function updateQuantity()
    {
        $cartId = $_POST['cartId'] ?? null;
        $quantity = $_POST['quantity'] ?? null;

        if ($cartId && $quantity) {
            $cartModel = new CartModel();
            $success = $cartModel->updateQuantity($_SESSION['user_id'], $cartId, $quantity);

            if ($success) {
                $_SESSION['status'] = 'Quantity updated successfully!';
                $_SESSION['status_type'] = 'success';
            } else {
                $_SESSION['status'] = 'Failed to update quantity.';
                $_SESSION['status_type'] = 'error';
            }
        } else {
            $_SESSION['status'] = 'Invalid input data.';
            $_SESSION['status_type'] = 'error';
        }

        header("Location: " . BASEURL . "/cart");
        exit();
    }

    public function deleteSelected()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id']) && isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
                $customerId = intval($_SESSION['user_id']);
                $selectedItems = $_POST['selected_items'];

                $cartModel = $this->model('CartModel');

                $success = true;
                foreach ($selectedItems as $cartId) {
                    if (!$cartModel->deleteItem($customerId, $cartId)) {
                        $success = false;
                        break;
                    }
                }

                if ($success) {
                    $_SESSION['success'] = "Item terpilih berhasil dihapus dari keranjang!";
                } else {
                    $_SESSION['error'] = "Gagal menghapus item terpilih, silakan coba lagi.";
                }
            } else {
                $_SESSION['error'] = "Anda harus login dan memilih item untuk dihapus.";
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

    public function getItemCountCart()
    {
        if (isset($_SESSION['user_id'])) {
            $customerId = $_SESSION['user_id'];
            $itemCount = $this->cartModel->getItemCountInCart($customerId);

            error_log("Customer ID: {$customerId}, Total Items in Cart: {$itemCount}");

            return $itemCount;
        }

        return 0;
    }
}
