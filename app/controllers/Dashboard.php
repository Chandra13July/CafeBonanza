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
        // Target yang bisa diubah dari pengaturan
        $targets = [
            'menu' => 25,
            'customer' => 50,
            'order' => 100,
            'profit' => 5000000
        ];

        // Mengambil data statistik
        $totalMenu = $this->MenuModel->getTotalMenu();
        $menuPercentage = ($totalMenu / $targets['menu']) * 100;

        $totalCustomer = $this->CustomerModel->getTotalCustomer();
        $customerPercentage = ($totalCustomer / $targets['customer']) * 100;

        $totalOrders = $this->OrderModel->getTotalOrders();
        $orderPercentage = ($totalOrders / $targets['order']) * 100;

        $currentMonthProfit = $this->OrderModel->getCurrentMonthCompletedProfit();
        $profitPercentage = ($currentMonthProfit / $targets['profit']) * 100;

        // Mendapatkan data total order per bulan untuk grafik
        $monthlyOrders = $this->OrderModel->getMonthlyTotalOrdersWithZero(date('Y'));

        // Mendapatkan data profit per bulan (Januari sampai Desember)
        $monthlyCompletedProfit1 = $this->OrderModel->getMonthlyCompletedProfit1(date('Y'));

        // Mendapatkan data menu populer
        $popularMenu = $this->MenuModel->getPopularMenu();

        // Menyusun data bulan (dari Januari sampai Desember)
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        // Menyusun data untuk view
        $data = [
            'totalMenu' => $totalMenu,
            'menuPercentage' => $menuPercentage,
            'totalCustomer' => $totalCustomer,
            'customerPercentage' => $customerPercentage,
            'totalOrders' => $totalOrders,
            'orderPercentage' => $orderPercentage,
            'currentMonthProfit' => $currentMonthProfit,
            'profitPercentage' => $profitPercentage,
            'targets' => $targets,
            'monthlyOrders' => $monthlyOrders,
            'monthlyCompletedProfit1' => $monthlyCompletedProfit1,
            'months' => $months,
            'popularMenu' => $popularMenu  // Menambahkan data menu populer
        ];

        // Menampilkan view
        $this->view('layout/header', $data);
        $this->view('layout/sidebar', $data);
        $this->view('admin/dashboard', $data);
    }
}
