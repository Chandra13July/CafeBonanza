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
        // Validasi input
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Email dan kata sandi harus diisi!";
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
        // Periksa apakah pengguna ada
        $user = $this->CustomerModel->findUserByEmail($email);
        // Debugging: Periksa data pengguna yang diambil
        error_log("Pengguna yang diambil: " . print_r($user, true)); // Log data pengguna yang diambil
        if ($user) {
            // Debugging: Periksa verifikasi kata sandi
            if (password_verify($password, $user['Password'])) {
                // Set variabel session
                unset($_SESSION['error']);
                unset($_SESSION['login_data']);
                $_SESSION['user_id'] = $user['CustomerId'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['ImageUrl'] = $user['ImageUrl'] ?? BASEURL . '/img/user.png'; // Set ImageUrl di session
                $_SESSION['success'] = "Login berhasil!";
                $_SESSION['redirect'] = true; // Tambahkan baris ini
                header('Location: ' . BASEURL . '/auth/login'); // Redirect ke login
                exit;
            } else {
                $_SESSION['error'] = "Email atau kata sandi salah!";
                header('Location: ' . BASEURL . '/auth/login');
                exit;
            }
        } else {
            $_SESSION['error'] = "Email atau kata sandi salah!";
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
    }
 }
