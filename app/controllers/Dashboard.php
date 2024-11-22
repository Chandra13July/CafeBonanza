<?php

class Dashboard extends Controller
{
    private $EmployeeModel;
    private $CustomerModel;
    private $MenuModel;

    public function __construct()
    {
        $this->MenuModel = $this->model('MenuModel');
        $this->EmployeeModel = $this->model('EmployeeModel');
        $this->CustomerModel = $this->model('CustomerModel');

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'You must log in first!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        $totalMenu = $this->MenuModel->getTotalMenu(); 
        $targetTotalMenu = 50; 
        $menuPercentage = ($totalMenu / $targetTotalMenu) * 100;

        $totalCustomer = $this->CustomerModel->getTotalCustomer();
        $targetTotalCustomer = 100;
        $customerPercentage = ($totalCustomer / $targetTotalCustomer) * 100;

        $data = [
            'totalMenu' => $totalMenu,
            'menuPercentage' => $menuPercentage,
            'totalCustomer' => $totalCustomer,
            'customerPercentage' => $customerPercentage
        ];

        $this->view('layout/header', $data);
        $this->view('layout/sidebar', $data);
        $this->view('admin/dashboard', $data);
    }
}
