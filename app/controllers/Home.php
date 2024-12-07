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
        $totalCartItems = $this->getItemCountCart();
        $latestContacts = $this->contactModel->getLatestContacts();

        $data = [
            'totalCartItems' => $totalCartItems,
            'latestContacts' => $latestContacts
        ];

        $this->view('layout/header');
        $this->view('layout/navbar', $data);
        $this->view('home/home', $data);
        $this->view('layout/footer');
    }

    public function about()
    {
        $totalCartItems = $this->getItemCountCart();

        $data = [
            'totalCartItems' => $totalCartItems
        ];

        $this->view('layout/header');
        $this->view('layout/navbar', $data);
        $this->view('home/about');
        $this->view('layout/footer');
    }

    public function menu()
    {
        $MenuModel = $this->model('MenuModel');
        $MenuItems = $MenuModel->getMenu();
        $totalCartItems = $this->getItemCountCart();

        $data = [
            'MenuItems' => $MenuItems,
            'Notification' => isset($_SESSION['notification']) ? $_SESSION['notification'] : null,
            'totalCartItems' => $totalCartItems
        ];

        if (isset($_SESSION['notification'])) {
            unset($_SESSION['notification']);
        }

        $this->view('layout/header');
        $this->view('layout/navbar', $data);
        $this->view('home/menu', $data);
        $this->view('layout/footer');
    }

    public function gallery()
    {
        $galleryItems = $this->model('GalleryModel')->getAllGallery();
        $totalCartItems = $this->getItemCountCart();

        $data = [
            'galleryItems' => $galleryItems,
            'totalCartItems' => $totalCartItems
        ];

        $this->view('layout/header');
        $this->view('layout/navbar', $data);
        $this->view('home/gallery', $data);
        $this->view('layout/footer');
    }

    public function contact()
    {
        $totalCartItems = $this->getItemCountCart();

        $data = [
            'totalCartItems' => $totalCartItems
        ];

        $this->view('layout/header');
        $this->view('layout/navbar', $data);
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

    public function getItemCountCart()
    {
        if (isset($_SESSION['user_id'])) {
            $customerId = $_SESSION['user_id'];
            $itemCount = $this->cartModel->getItemCountInCart($customerId);

            error_log("Customer ID: {$customerId}, Total Items in Cart: {$itemCount}");

            return $itemCount;
        }

        return 0;
    }
}
