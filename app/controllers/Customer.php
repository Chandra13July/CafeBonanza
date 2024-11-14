<?php

class Customer extends Controller
{
    private $customerModel;

    public function __construct()
    {
        $this->customerModel = $this->model('CustomerModel');
    }

    public function index()
    {
        $customer = $this->customerModel->getAllCustomer();

        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/customer', ['customer' => $customer]);
    }

    public function btnAddCustomer()
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
                'imageUrl' => $this->uploadImage()
            ];

            if ($this->customerModel->addCustomer($data)) {
                $_SESSION['success'] = "Customer berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Penambahan customer gagal, silakan coba lagi.";
            }
            header("Location: " . BASEURL . "/customer/index");
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
                    $imageUploadPath = 'upload/customer/' . $newImageName;

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

    public function btnEditCustomer()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->customerModel->findUserById($_POST['CustomerId']);
            $data = [
                'CustomerId' => $_POST['CustomerId'],
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'gender' => trim($_POST['gender']),
                'dateOfBirth' => trim($_POST['dateOfBirth']),
                'address' => trim($_POST['address']),
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if ($_FILES['imageUrl']['name'] != "") {
                $data['imageUrl'] = $this->uploadImage();
            } else {
                $data['imageUrl'] = $user['ImageUrl'];
            }
            
            if ($this->customerModel->editCustomer($data)) {
                $_SESSION['success'] = "Customer berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Pembaharuan customer gagal.";
            }

            header("Location: " . BASEURL . "/customer/index");
            exit();
        }
    }

    public function btnDeleteCustomer()
    {
        if (isset($_POST['CustomerId'])) {
            $customerid = $_POST['CustomerId'];

            if ($this->customerModel->deleteCustomer($customerid)) {
                $_SESSION['success'] = "Customer berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus customer, silakan coba lagi.";
            }
        } else {
            $_SESSION['error'] = "Customer ID tidak valid.";
        }

        header("Location: " . BASEURL . "/customer/index");
        exit();
    }
}
