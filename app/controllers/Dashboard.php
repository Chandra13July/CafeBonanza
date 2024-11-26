<?php

class Dashboard extends Controller
{
    private $EmployeeModel;
    private $CustomerModel;
    private $MenuModel;
    private $OrderModel;

    public function __construct()
    {
        $this->MenuModel = $this->model('MenuModel');
        $this->EmployeeModel = $this->model('EmployeeModel');
        $this->CustomerModel = $this->model('CustomerModel');
        $this->OrderModel = $this->model('OrderModel');

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'You must log in first!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        // Get total menu data and percentage
        $totalMenu = $this->MenuModel->getTotalMenu();
        $targetTotalMenu = 50;
        $menuPercentage = ($totalMenu / $targetTotalMenu) * 100;

        // Get total customer data and percentage
        $totalCustomer = $this->CustomerModel->getTotalCustomer();
        $targetTotalCustomer = 100;
        $customerPercentage = ($totalCustomer / $targetTotalCustomer) * 100;

        // Get total orders data and percentage
        $totalOrders = $this->OrderModel->getTotalOrders();
        $targetTotalOrder = 100;
        $orderPercentage = ($totalOrders / $targetTotalOrder) * 100;

        // Get profit for the current month (status 'Completed') and percentage
        $currentMonthProfit = $this->OrderModel->getCurrentMonthCompletedProfit();
        $targetProfit = 3000000; // Target keuntungan bulan ini (Rp 3 juta)
        $profitPercentage = ($currentMonthProfit / $targetProfit) * 100;

        // Prepare data for the view
        $data = [
            'totalMenu' => $totalMenu,
            'menuPercentage' => $menuPercentage,
            'totalCustomer' => $totalCustomer,
            'customerPercentage' => $customerPercentage,
            'totalOrders' => $totalOrders,
            'orderPercentage' => $orderPercentage,
            'currentMonthProfit' => $currentMonthProfit,
            'profitPercentage' => $profitPercentage // Tambahkan persentase keuntungan bulan ini
        ];

        // Load views
        $this->view('layout/header', $data);
        $this->view('layout/sidebar', $data);
        $this->view('admin/dashboard', $data);
    }
}
