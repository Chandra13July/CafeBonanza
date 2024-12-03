<?php

class Cart extends Controller
{
    private $cartModel;
    private $orderModel;

    public function __construct()
    {
        $this->cartModel = $this->model('CartModel');
        $this->orderModel = $this->model('OrderModel');

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

    public function btnCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id'])) {
                $customerId = intval($_SESSION['user_id']);
                $paymentMethod = $_POST['payment-method'] ?? null;
                $selectedItems = $_POST['selected_items'] ?? null;
                $orderDetails = $_POST['cartItems'] ?? null;  

                if (is_array($selectedItems)  && $paymentMethod) {
                    $cartModel = $this->model('CartModel');
                    $orderModel = $this->model('OrderModel'); 

                    $orderId = $orderModel->checkout($customerId,  $paymentMethod, $selectedItems);

                    if (empty($selectedItems) || empty($paymentMethod)) {
                        $_SESSION['error'] = "Anda harus memilih item dan metode pembayaran.";
                        header('Location: ' . BASEURL . '/cart');
                        exit();
                    }

                    if ($orderId) {
                        foreach ($selectedItems as $cartId) {
                            $cartModel->deleteItem($customerId, $cartId);
                        }

                        $_SESSION['success'] = "Pesanan berhasil dibuat! ID Pesanan: " . $orderId;
                        header('Location: ' . BASEURL . '/cart/index' . $orderId); 
                    } else {
                        $_SESSION['error'] = "Gagal membuat pesanan, silakan coba lagi.";
                        header('Location: ' . BASEURL . '/cart');
                    }
                } else {
                    $_SESSION['error'] = "Anda harus memilih item dan metode pembayaran.";
                    header('Location: ' . BASEURL . '/cart');
                }
            } else {
                $_SESSION['error'] = "Anda harus login terlebih dahulu.";
                header('Location: ' . BASEURL . '/auth/login');
            }
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
