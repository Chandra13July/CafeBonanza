<?php

class Profile extends Controller
{
    private $customerModel;

    public function __construct()
    {
        $this->customerModel = $this->model('CustomerModel');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            $customerModel = $this->model('CustomerModel');
            $customerData = $customerModel->getUserByUsername($username);

            if ($customerData) {
                $this->view('layout/header');
                $this->view('home/profile', $customerData);
            } else {
                echo "Data pengguna tidak ditemukan!";
            }
        } else {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function btnEditProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'CustomerId' => $_SESSION['user_id'], // Pastikan user sudah login
                'username' => htmlspecialchars(trim($_POST['username'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
                'phone' => htmlspecialchars(trim($_POST['phone'])),
                'gender' => htmlspecialchars(trim($_POST['gender'])),
                'dateOfBirth' => htmlspecialchars(trim($_POST['dateOfBirth'])),
                'address' => htmlspecialchars(trim($_POST['address']))
            ];

            // Validasi input
            if (empty($data['username']) || empty($data['email']) || empty($data['phone']) || empty($data['address'])) {
                $_SESSION['error'] = "Please fill in all required fields.";
                header("Location: " . BASEURL . "/profile/index");
                exit();
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email format.";
                header("Location: " . BASEURL . "/profile/index");
                exit();
            }

            // Update profil menggunakan model
            if ($this->customerModel->editProfile($data)) {
                $_SESSION['success'] = "Profile updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update profile, please try again.";
            }

            header("Location: " . BASEURL . "/profile/index");
            exit();
        }
    }

    private function uploadImage()
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $imageName = $_FILES['image']['name'];
            $imageTmpName = $_FILES['image']['tmp_name'];
            $imageSize = $_FILES['image']['size'];
            $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageExt, $allowed)) {
                if ($imageSize < 5000000) { // Ukuran maksimum 5MB
                    $newImageName = uniqid('', true) . '.' . $imageExt;
                    $imageUploadPath = 'upload/customer/' . $newImageName;

                    if (move_uploaded_file($imageTmpName, $imageUploadPath)) {
                        return $imageUploadPath;
                    } else {
                        return ['error' => 'Gagal mengunggah gambar.'];
                    }
                } else {
                    return ['error' => 'Ukuran gambar terlalu besar.'];
                }
            } else {
                return ['error' => 'Jenis file gambar tidak valid.'];
            }
        }
        return null; // Tidak ada file diunggah
    }

    public function btnEditImage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imagePath = $this->uploadImage(); // Panggil fungsi uploadImage

            if ($imagePath) {
                $data = [
                    'CustomerId' => $_SESSION['user_id'], // Pastikan user sudah login
                    'imageUrl' => BASEURL . '/' . $imagePath
                ];

                // Update URL gambar menggunakan model
                if ($this->customerModel->editImage($data)) {
                    $_SESSION['ImageUrl'] = $data['imageUrl'];  // Perbarui ImageUrl di session
                    echo json_encode(['success' => true, 'imageUrl' => $data['imageUrl']]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update profile image.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
            }
            exit();
        }
    }
}
