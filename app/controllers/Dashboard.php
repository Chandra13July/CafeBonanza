<?php

class Dashboard extends Controller
{
    private $EmployeeModel;

    public function __construct()
    {
        $this->EmployeeModel = $this->model('EmployeeModel');
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'Anda harus login terlebih dahulu!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }      

    public function index()
    {
        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/dashboard');
    }

}
