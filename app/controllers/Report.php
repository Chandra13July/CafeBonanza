<?php

class Report extends Controller
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = $this->model('OrderModel');
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'Anda harus login terlebih dahulu!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        $orders = $this->orderModel->getOrderReport(); // Mengambil laporan order

        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/report', ['orders' => $orders]);
    }

    public function filterReport()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Ensure data is sanitized and not null
            $startDate = trim($_POST['startDate'] ?? '');
            $endDate = trim($_POST['endDate'] ?? '');

            // Call the updated method to fetch filtered data based on date range
            $orders = $this->orderModel->getOrderReport($startDate, $endDate);

            // Pass the filtered data to the view
            $this->view('layout/header');
            $this->view('layout/sidebar');
            $this->view('admin/report', ['orders' => $orders]);
        }
    }

    public function orderReceipt($orderId)
    {

        if (empty($orderId)) {
            $_SESSION['flash_message'] = 'Order ID tidak ditemukan.';
            header('Location: ' . BASEURL . '/report');
            exit;
        }

        try {
            $receipt = $this->orderModel->getOrderReceipt($orderId);

            // Tampilkan struk di view
            $this->view('layout/header');
            $this->view('layout/sidebar');
            $this->view('admin/receipt', ['receipt' => $receipt]);
        } catch (Exception $e) {
            $_SESSION['flash_message'] = 'Gagal mengambil struk pesanan: ' . $e->getMessage();
            header('Location: ' . BASEURL . '/report');
            exit;
        }
    }

    public function btnEditOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order = $this->orderModel->findOrderById($_POST['OrderId']);

            if (!isset($_POST['OrderId']) || empty($_POST['OrderId'])) {
                $_SESSION['error'] = "Order ID tidak valid.";
                header("Location: " . BASEURL . "/report/index");
                exit();
            }


            $data = [
                'OrderId' => $_POST['OrderId'],
                'Total' => trim($_POST['total']),
                'Paid' => trim($_POST['paid']),
                'Change' => trim($_POST['change']),
                'PaymentMethod' => trim($_POST['paymentMethod']),
                'Status' => trim($_POST['status']),
            ];

            if ($this->orderModel->editOrder($data)) {
                $_SESSION['success'] = "Order berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Pembaharuan order gagal.";
            }

            header("Location: " . BASEURL . "/report/index");
            exit();
        }
    }
}
