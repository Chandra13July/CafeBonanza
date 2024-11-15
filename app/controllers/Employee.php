<?php

class Employee extends Controller
{
    private $employeeModel;

    public function __construct()
    {
        $this->employeeModel = $this->model('EmployeeModel');
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        $employee = $this->employeeModel->getAllEmployee();

        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/employee', ['employee' => $employee]);
    }

    public function btnAddEmployee()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => $_POST['password'],
                'phone' => trim($_POST['phone']),
                'gender' => trim($_POST['gender']),
                'dateOfBirth' => trim($_POST['dateOfBirth']),
                'address' => trim($_POST['address']),
                'role' => trim($_POST['role']),
                'imageUrl' => $this->uploadImage()
            ];

            if ($this->employeeModel->addEmployee($data)) {
                $_SESSION['success'] = "Employee berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Penambahan employee gagal, silakan coba lagi.";
            }
            header("Location: " . BASEURL . "/employee/index");
            exit();
        }
    }

    private function uploadImage()
    {
        if (isset($_FILES['imageUrl']) && $_FILES['imageUrl']['error'] === 0) {
            $imageName = $_FILES['imageUrl']['name'];
            $imageTmpName = $_FILES['imageUrl']['tmp_name'];
            $imageSize = $_FILES['imageUrl']['size'];
            $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageExt, $allowed)) {
                if ($imageSize < 5000000) {
                    $newImageName = uniqid('', true) . '.' . $imageExt;
                    $imageUploadPath = 'upload/employee/' . $newImageName;

                    if (move_uploaded_file($imageTmpName, $imageUploadPath)) {
                        return $imageUploadPath;
                    } else {
                        $_SESSION['error'] = "Gagal mengunggah gambar.";
                        return false;
                    }
                } else {
                    $_SESSION['error'] = "Ukuran gambar terlalu besar.";
                    return false;
                }
            } else {
                $_SESSION['error'] = "Jenis file gambar tidak valid.";
                return false;
            }
        }
        return null;
    }

    public function btnEditEmployee()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->employeeModel->findUserById($_POST['EmployeeId']);
            $data = [
                'EmployeeId' => $_POST['EmployeeId'],
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'gender' => trim($_POST['gender']),
                'dateOfBirth' => trim($_POST['dateOfBirth']),
                'address' => trim($_POST['address']),
                'role' => trim($_POST['role']),
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if ($_FILES['imageUrl']['name'] != "") {
                $data['imageUrl'] = $this->uploadImage();
            } else {
                $data['imageUrl'] = $user['ImageUrl'];
            }
            
            if ($this->employeeModel->editEmployee($data)) {
                $_SESSION['success'] = "Employee berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Pembaharuan employee gagal.";
            }

            header("Location: " . BASEURL . "/employee/index");
            exit();
        }
    }

    public function btnDeleteEmployee()
    {
        if (isset($_POST['EmployeeId'])) {
            $employeeId = $_POST['EmployeeId'];

            if ($this->employeeModel->deleteEmployee($employeeId)) {
                $_SESSION['success'] = "Employee berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus employee, silakan coba lagi.";
            }
        } else {
            $_SESSION['error'] = "Employee ID tidak valid.";
        }

        header("Location: " . BASEURL . "/employee/index");
        exit();
    }
}
