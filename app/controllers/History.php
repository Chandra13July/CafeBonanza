<?php

class History extends Controller
{
    private $orderModel;
    private $customerModel;

    public function __construct()
    {
        $this->orderModel = $this->model('OrderModel');
        $this->customerModel = $this->model('CustomerModel');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            // Ambil data customer berdasarkan username
            $customerModel = $this->model('CustomerModel');
            $customerData = $customerModel->getUserByUsername($username);

            if ($customerData) {
                // Ambil CustomerId langsung dari session
                $customerId = intval($_SESSION['user_id']);

                // Ambil histori order berdasarkan CustomerId
                $orderHistory = $this->orderModel->getOrderHistory($customerId); // Menggunakan $customerId yang diambil dari session

                // Kirimkan data ke tampilan
                $this->view('layout/header');
                $this->view('layout/navbar');
                $this->view('home/history', ['customerData' => $customerData, 'orderHistory' => $orderHistory]);
            } else {
                echo "Data pengguna tidak ditemukan!";
            }
        } else {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }
}
