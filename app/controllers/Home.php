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
            // Pastikan user sudah login dan memiliki username
            if (isset($_SESSION['username'])) {
                // Ambil data pengguna dari database berdasarkan username yang ada di session
                $username = $_SESSION['username'];
    
                // Memuat model UserModel
                $userModel = $this->model('UserModel');
                $customerData = $userModel->getUserByUsername($username); // Ambil data berdasarkan username
    
                if ($customerData) {
                    // Kirimkan data ke view profile
                    $this->view('layout/header');
                    $this->view('layout/sidebarprofil');
                    $this->view('home/profile', $customerData); // Kirim data ke view profile
                } else {
                    // Jika tidak ada data pengguna, beri pesan error
                    echo "Data pengguna tidak ditemukan!";
                }
            } else {
                // Jika user tidak login, arahkan ke halaman login
                header('Location: ' . BASEURL . '/login');
                exit;
            }
        }
     
      
}
