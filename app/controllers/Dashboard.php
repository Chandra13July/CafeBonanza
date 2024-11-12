<?php

class Dashboard extends Controller
{
    private $EmployeeModel;

    public function __construct()
    {
        $this->EmployeeModel = $this->model('EmployeeModel');
    }

    public function index()
    {
        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/dashboard');
    }

}
