<?php

class Home extends Controller
{

    private $contactModel;

    public function __construct()
    {
        $this->contactModel = $this->model('ContactModel');
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'type' => $_POST['type'],
                'message' => trim($_POST['message']),
            ];

            if ($this->contactModel->addContact($data)) {
                $_SESSION['success'] = "Kontak berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Penambahan kontak gagal, silakan coba lagi.";
            }
            header("Location: " . BASEURL . "/home/contact");
            exit();
        }
    }
    
    public function profile()
    {
        $this->view('layout/header');
        $this->view('home/profile');
    }
}
