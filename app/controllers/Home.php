<?php

class Home extends Controller
{
    private $contactModel;
    private $customerModel;
    private $cartModel;

    public function __construct()
    {
        $this->contactModel = $this->model('ContactModel');
        $this->customerModel = $this->model('CustomerModel');
        $this->cartModel = $this->model('CartModel');
    }

    public function index()
    {
        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/home');
        $this->view('layout/footer');
    }

    public function about()
    {
        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/about');
        $this->view('layout/footer');
    }

    public function menu()
    {
        $MenuModel = $this->model('MenuModel');

        $MenuItems = $MenuModel->getMenu(); 

        $data = [
            'MenuItems' => $MenuItems,
            'Notification' => isset($_SESSION['notification']) ? $_SESSION['notification'] : null,
        ];

        if (isset($_SESSION['notification'])) {
            unset($_SESSION['notification']);
        }

        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/menu', $data);
        $this->view('layout/footer');
    }

    public function gallery()
    {
        $galleryItems = $this->model('GalleryModel')->getAllGallery();

        $data = [
            'galleryItems' => $galleryItems
        ];

        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/gallery', $data);
        $this->view('layout/footer');
    }

    public function contact()
    {
        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/contact');
        $this->view('layout/footer');
    }

    public function btnContact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => htmlspecialchars(trim($_POST['name'])),
                'email' => htmlspecialchars(trim($_POST['email'])),
                'type' => htmlspecialchars($_POST['type']),
                'message' => htmlspecialchars(trim($_POST['message'])),
            ];

            if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
                $_SESSION['error'] = "Please fill in all required fields.";
                header("Location: " . BASEURL . "/home/contact");
                exit();
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email format.";
                header("Location: " . BASEURL . "/home/contact");
                exit();
            }

            if ($this->contactModel->addContact($data)) {
                $_SESSION['success'] = "Contact has been successfully added!";
            } else {
                $_SESSION['error'] = "Failed to add contact, please try again.";
            }

            header("Location: " . BASEURL . "/home/contact");
            exit();
        }
    }

    public function profile()
    {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            $customerModel = $this->model('CustomerModel');
            $customerData = $customerModel->getUserByUsername($username);

            if ($customerData) {
                $this->view('layout/header');
                $this->view('layout/sidebarprofil');
                $this->view('home/profile', $customerData);
            } else {
                echo "Data pengguna tidak ditemukan!";
            }
        } else {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function cart()
    {
        $this->view('layout/header');
        $this->view('home/cart');
    }
}
