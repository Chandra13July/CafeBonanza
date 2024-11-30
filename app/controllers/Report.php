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
}
