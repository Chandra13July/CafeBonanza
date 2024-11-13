<?php

class Auth extends Controller
{
    private $EmployeeModel;

    public function __construct()
    {
        $this->EmployeeModel = $this->model('EmployeeModel');
    }

    public function loginAdmin()
    {
        $this->view('layout/header');
        $this->view('auth/loginAdmin');
    }

    public function logout()
    {
        session_destroy(); 
        header('Location: ' . BASEURL . '/auth/login'); 
        exit();
    }

    public function btnLoginAdmin()
    {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Email dan password harus diisi!";
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }

        $user = $this->EmployeeModel->findUserByEmail($email);

        if ($user) {
            if (password_verify($password, $user['Password'])) {
                unset($_SESSION['error']);
                unset($_SESSION['login_data']);
                $_SESSION['user_id'] = $user['EmployeeId'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['ImageUrl'] = $user['ImageUrl'] ?? BASEURL . '/img/user.png';

                $role = $user['Role'];

                $_SESSION['success'] = "{$user['Username']} berhasil login sebagai {$role}!";
                $_SESSION['redirect_url'] = ($role == 'Admin') ? '/dashboard/index' : '/order/index';
                header('Location: ' . BASEURL . '/auth/loginAdmin');
                exit;
            } else {
                $_SESSION['error'] = "Password salah!";
                header('Location: ' . BASEURL . '/auth/loginAdmin');
                exit;
            }
        } else {
            $_SESSION['error'] = "Email tidak ditemukan!";
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }
}
