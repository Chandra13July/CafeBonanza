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

            $customerModel = $this->model('CustomerModel');
            $customerData = $customerModel->getUserByUsername($username);

            if ($customerData) {
                $this->view('layout/header');
                $this->view('home/history', $customerData);
            } else {
                echo "Data pengguna tidak ditemukan!";
            }
        } else {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }
}
