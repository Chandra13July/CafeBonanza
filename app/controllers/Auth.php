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

    public function forgot()
    {
        $this->view('layout/header');
        $this->view('auth/forgot');
    }

    public function resetPassword()
    {
        $this->view('layout/header');
        $this->view('auth/reset');
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
            $_SESSION['error'] = "Email and password must be filled!";
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

                $_SESSION['success'] = "{$user['Username']} logged in successfully as {$role}!";

                if ($role == 'Admin') {
                    $_SESSION['redirect_url'] = '/dashboard/index';
                    header('Location: ' . BASEURL . '/dashboard/index');
                } else {
                    $_SESSION['redirect_url'] = '/order/index';
                    header('Location: ' . BASEURL . '/order/index');
                }
                exit;
            } else {
                $_SESSION['error'] = "Incorrect password!";
                header('Location: ' . BASEURL . '/auth/loginAdmin');
                exit;
            }
        } else {
            $_SESSION['error'] = "Email is not registered!";
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
            $_SESSION['error'] = "All fields must be filled!";
            $_SESSION['signup_data'] = $data;
            header('Location: ' . BASEURL . '/auth/signup');
            exit;
        }

        if (!preg_match('/^[a-zA-Z]{5,}$/', $data['username'])) {
            $_SESSION['error'] = "Username must be at least 5 characters long and contain only letters!";
            $_SESSION['signup_data'] = $data;
            header('Location: ' . BASEURL . '/auth/signup');
            exit;
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $data['password'])) {
            $_SESSION['error'] = "Password must be at least 8 characters long, contain uppercase and lowercase letters, a number, and a special character!";
            $_SESSION['signup_data'] = $data;
            header('Location: ' . BASEURL . '/auth/signup');
            exit;
        }

        if ($data['password'] !== $data['confirm_password']) {
            $_SESSION['error'] = "Passwords do not match!";
            $_SESSION['signup_data'] = $data;
            header('Location: ' . BASEURL . '/auth/signup');
            exit;
        }

        if ($this->CustomerModel->findUserByEmail($data['email'])) {
            $_SESSION['error'] = "Email is already registered!";
            $_SESSION['signup_data'] = $data;
            header('Location: ' . BASEURL . '/auth/signup');
            exit;
        }

        if ($this->CustomerModel->signup($data)) {
            unset($_SESSION['error']);
            unset($_SESSION['signup_data']);
            $_SESSION['success'] = "Registration successful! Please log in.";
            $_SESSION['signup_success'] = true;
            header("Location: " . BASEURL . "/auth/signup");
            exit();
        } else {
            $_SESSION['error'] = "Registration failed, please try again.";
            $_SESSION['signup_data'] = $data;
            header("Location: " . BASEURL . "/auth/signup");
            exit();
        }
    }

    public function btnVerifyEmail()
    {
        $email = trim($_POST['reset_email']);

        if (empty($email)) {
            $_SESSION['error'] = "Email must be filled!";
            header('Location: ' . BASEURL . '/auth/forgot');
            exit;
        }

        $user = $this->CustomerModel->findUserByEmail($email);

        if ($user) {
            unset($_SESSION['error']);
            $_SESSION['emailVerified'] = true;
            $_SESSION['verifiedEmail'] = $email;
            $_SESSION['success'] = "Email successfully verified! Please reset your password.";
            header('Location: ' . BASEURL . '/auth/forgot#reset-password-form'); // Go directly to the reset password form
            exit;
        } else {
            $_SESSION['error'] = "Email not registered!";
            header('Location: ' . BASEURL . '/auth/forgot');
            exit;
        }
    }

    public function btnResetPassword()
    {
        if (!isset($_SESSION['emailVerified']) || $_SESSION['emailVerified'] !== true) {
            $_SESSION['error'] = "Unauthorized action!";
            header('Location: ' . BASEURL . '/auth/resetPassword');
            exit;
        }

        $email = $_SESSION['verifiedEmail'];
        $newPassword = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if (empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = "All fields must be filled!";
            header('Location: ' . BASEURL . '/auth/resetPassword');
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "Passwords do not match!";
            header('Location: ' . BASEURL . '/auth/resetPassword');
            exit;
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $newPassword)) {
            $_SESSION['error'] = "Password must contain at least 8 characters, uppercase and lowercase letters, a number, and a symbol!";
            header('Location: ' . BASEURL . '/auth/resetPassword');
            exit;
        }

        if ($this->CustomerModel->resetPassword($email, $newPassword)) {
            unset($_SESSION['error'], $_SESSION['emailVerified'], $_SESSION['verifiedEmail']);
            $_SESSION['success'] = "Password successfully reset! You can log in with the new password.";
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        } else {
            $_SESSION['error'] = "Failed to reset password, please try again!";
            header('Location: ' . BASEURL . '/auth/resetPassword');
            exit;
        }
    }
}
