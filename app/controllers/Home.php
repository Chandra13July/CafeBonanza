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
    public function menu($category = null)
    {
        // Tangkap parameter search query (jika ada)
        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Dapatkan data menu berdasarkan kategori atau pencarian
        if ($searchQuery) {
            $MenuItems = $this->model('MenuCustModel')->searchMenu($searchQuery);
        } else {
            $MenuItems = $this->model('MenuCustModel')->getMenu($category);
        }
        
        // Siapkan data untuk view
        $data = [
            'MenuItems' => $MenuItems,
            'SelectedCategory' => $category,
            'SearchQuery' => $searchQuery,
            'Notification' => isset($_SESSION['notification']) ? $_SESSION['notification'] : null,
        ];
        
        // Hapus notifikasi setelah ditampilkan
        if (isset($_SESSION['notification'])) {
            unset($_SESSION['notification']);
        }
        
        // Panggil view
        $this->view('layout/header');
        $this->view('layout/navbar');
        $this->view('home/menu', $data);
        $this->view('layout/footer');
    }

    // Menambahkan item ke keranjang (cart)
    public function addToCart() {
        // Pastikan ada data yang diterima
        if (!isset($_POST['MenuId']) || !isset($_POST['Quantity'])) {
            $_SESSION['notification'] = 'Input tidak valid';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $menuId = $_POST['MenuId'];
        $quantity = $_POST['Quantity'];
        $menuName = $_POST['MenuName'];        
        $price = $_POST['Price'];       
        $userId = $_SESSION['user_id']; // Ambil user_id dari session jika pengguna login

        // Cek apakah stok cukup
        $menuModel = $this->model('MenuCustModel');
        $menuItem = $menuModel->getStock($menuId); // Ambil stock dari database

        if (!$menuItem || $menuItem['Stock'] < $quantity) {
            $_SESSION['notification'] = 'Stok tidak cukup';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Menambahkan item ke cart melalui CartModel
        $cartModel = $this->model('CartModel');
        $cartModel->addToCart($menuId, $menuName, $price, $quantity, $userId);

        // Update stock di database
        $menuModel->updateStock($menuId, $quantity);

        $_SESSION['notification'] = 'Berhasil menambahkan ke keranjang';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Mendapatkan cart untuk ditampilkan
    public function getCart() {
        $userId = $_SESSION['user_id']; // Ambil user_id dari session
        $cartModel = $this->model('CartModel');
        $cartItems = $cartModel->getCart($userId);

        $data = [
            'CartItems' => $cartItems
        ];

        // Panggil view untuk menampilkan cart
        $this->view('cart/index', $data);
    }

    // Proses pembelian item dalam keranjang
    public function buyNow() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil data dari form POST
            $cartModel = $this->model('CartModel');
            $userId = $_SESSION['user_id'];
            $cartItems = $cartModel->getCart($userId); // Ambil semua item di keranjang

            foreach ($cartItems as $item) {
                // Update stock per item yang dibeli
                $menuId = $item['MenuId'];
                $quantity = $item['Quantity'];

                // Cek apakah stok cukup sebelum mengurangi
                $menuModel = $this->model('MenuCustModel');
                $menuItem = $menuModel->getStock($menuId);
                if ($menuItem['Stock'] < $quantity) {
                    $_SESSION['notification'] = 'Stok tidak cukup untuk beberapa item';
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit;
                }

                // Update stok setelah pembelian
                $menuModel->updateStock($menuId, $quantity);
            }

            // Proses checkout (misalnya, simpan pesanan, kurangi saldo, dll)
            // Setelah pembelian selesai, hapus item dari keranjang
            $cartModel->clearCart($userId);
            $_SESSION['notification'] = 'Pembelian berhasil, terima kasih!';
            header('Location: /cart/success');
            exit;
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
        public function cart()
    {
        $this->view('layout/header');
        $this->view('home/cart');     
    }

        
     
      
}
