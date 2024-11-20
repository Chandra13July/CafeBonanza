<?php

class Auth extends Controller
{
    private $EmployeeModel;
    private $CustomerModel;

    public function __construct()
    {
        $this->EmployeeModel = $this->model('EmployeeModel');
        $this->CustomerModel = $this->model('CustomerModel');
    }

    public function loginAdmin()
    {
        $this->view('layout/header');
        $this->view('auth/loginAdmin');
    }

    public function login()
    {
        $this->view('layout/header');
        $this->view('auth/login');
    }
    public function signup()
    {
        $this->view('layout/header');
        $this->view('auth/signup');
    }

    public function logoutAdmin()
    {
        session_destroy();
        header('Location: ' . BASEURL . '/auth/loginAdmin');
        exit();
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

    public function btnLogin()
    {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
    
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Email and password must be filled!";
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
    
        $user = $this->CustomerModel->findUserByEmail($email);
    
        if (!$user) {
            $_SESSION['error'] = "Email is not registered!";
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
    
        if (!password_verify($password, $user['Password'])) {
            $_SESSION['error'] = "Incorrect password!";
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
    
        unset($_SESSION['error']);
        unset($_SESSION['login_data']);
        $_SESSION['user_id'] = $user['CustomerId'];
        $_SESSION['username'] = $user['Username'];
        $_SESSION['ImageUrl'] = $user['ImageUrl'] ?? BASEURL . '/img/user.png';
        
        $_SESSION['success'] = $user['Username'] . " logged in successfully!";
        
        header('Location: ' . BASEURL . '/auth/login'); 
        exit;
    }
    
    public function btnSignup()
    {
        $data = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'confirm_password' => $_POST['confirm_password']
        ];

        if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['confirm_password'])) {
            $_SESSION['error'] = "Semua kolom harus diisi!";
            $_SESSION['signup_data'] = $data;
            header('Location: ' . BASEURL . '/auth/signup');
            exit;
        }

        if ($data['password'] !== $data['confirm_password']) {
            $_SESSION['error'] = "Kata sandi tidak cocok!";
            $_SESSION['signup_data'] = $data;
            header('Location: ' . BASEURL . '/auth/signup');
            exit;
        }

        if ($this->CustomerModel->findUserByEmail($data['email'])) {
            $_SESSION['error'] = "Email sudah terdaftar!";
            $_SESSION['signup_data'] = $data;
            header('Location: ' . BASEURL . '/auth/signup');
            exit;
        }

        if ($this->CustomerModel->signup($data)) {
            unset($_SESSION['error']);
            unset($_SESSION['signup_data']);
            $_SESSION['success'] = "Pendaftaran berhasil! Silakan masuk.";
            $_SESSION['signup_success'] = true;
            header("Location: " . BASEURL . "/auth/signup");
            exit();
        } else {
            $_SESSION['error'] = "Pendaftaran gagal, silakan coba lagi.";
            $_SESSION['signup_data'] = $data;
            header("Location: " . BASEURL . "/auth/signup");
            exit();
        }
    }
}
