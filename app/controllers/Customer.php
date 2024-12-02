<?php

class Customer extends Controller
{
    private $customerModel;

    public function __construct()
    {
        $this->customerModel = $this->model('CustomerModel');
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'Anda harus login terlebih dahulu!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
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

            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $phone = trim($_POST['phone']);
            $gender = trim($_POST['gender']);
            $dateOfBirth = trim($_POST['dateOfBirth']);
            $address = trim($_POST['address']);

            $errors = [];

            if (empty($username)) {
                $errors[] = "Username is required.";
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }
            if (empty($password)) {
                $errors[] = "Password is required.";
            } elseif (!preg_match('/[A-Z]/', $password)) {
                $errors[] = "Password must contain at least one uppercase letter.";
            } elseif (!preg_match('/[a-z]/', $password)) {
                $errors[] = "Password must contain at least one lowercase letter.";
            } elseif (!preg_match('/[0-9]/', $password)) {
                $errors[] = "Password must contain at least one number.";
            } elseif (!preg_match('/[\W_]/', $password)) {
                $errors[] = "Password must contain at least one special character.";
            }
            if (empty($phone) || !preg_match('/^[0-9]{10,12}$/', $phone)) {
                $errors[] = "Phone number must be 10 to 12 digits.";
            }
            if (empty($gender)) {
                $errors[] = "Gender is required.";
            }
            if (empty($dateOfBirth) || !strtotime($dateOfBirth)) {
                $errors[] = "Invalid date of birth.";
            }
            if (empty($address)) {
                $errors[] = "Address is required.";
            }
            if (!empty($errors)) {
                $_SESSION['error'] = implode("<br>", $errors);
                header("Location: " . BASEURL . "/customer/add");
                exit();
            }

            $data = [
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT), 
                'phone' => $phone,
                'gender' => $gender,
                'dateOfBirth' => $dateOfBirth,
                'address' => $address,
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

            $errors = [];

            $data = [
                'CustomerId' => $_POST['CustomerId'],
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'gender' => trim($_POST['gender']),
                'dateOfBirth' => trim($_POST['dateOfBirth']),
                'address' => trim($_POST['address']),
            ];

            if (empty($data['username'])) {
                $errors['username'] = "Username is required.";
            }

            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email format.";
            }

            if (empty($data['phone']) || !preg_match('/^[0-9]{10,12}$/', $data['phone'])) {
                $errors['phone'] = "Phone number must be 10 to 12 digits.";
            }

            if (empty($data['gender'])) {
                $errors['gender'] = "Gender is required.";
            }

            if (empty($data['dateOfBirth']) || !strtotime($data['dateOfBirth'])) {
                $errors['dateOfBirth'] = "Invalid date of birth.";
            }

            if (empty($data['address'])) {
                $errors['address'] = "Address is required.";
            }

            if ($_FILES['imageUrl']['name'] != "") {
                $data['imageUrl'] = $this->uploadImage();
            } else {
                $data['imageUrl'] = $user['ImageUrl']; 
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['formData'] = $_POST; 
                header("Location: " . BASEURL . "/customer/edit/" . $_POST['CustomerId']);
                exit();
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
