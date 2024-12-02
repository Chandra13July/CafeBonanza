<?php

class App
{
    // Properti default untuk controller, method, dan parameter
    protected $controller = 'Home'; // Controller default
    protected $method = 'index';    // Method default
    protected $params = [];         // Parameter default (kosong)

    public function __construct()
    {
        // Memproses URL untuk menentukan controller, method, dan parameter
        $url = $this->parseURL();

        // Mengecek apakah file controller sesuai URL ada
        if ($url && file_exists('../app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0]; // Menetapkan controller dari URL
            unset($url[0]); // Menghapus controller dari URL untuk parsing berikutnya
        }

        // Memuat file controller
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller; // Membuat instance controller

        // Mengecek apakah method dalam controller valid
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1]; // Menetapkan method dari URL
            unset($url[1]); // Menghapus method dari URL untuk parsing berikutnya
        }

        // Menetapkan parameter dari URL atau menggunakan array kosong
        $this->params = $url ? array_values($url) : [];

        // Menjalankan controller dan method dengan parameter
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        // Memproses URL dari parameter GET['url']
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/'); // Menghapus '/' di akhir URL
            $url = filter_var($url, FILTER_SANITIZE_URL); // Membersihkan URL dari karakter berbahaya
            $url = explode('/', $url); // Memecah URL menjadi array berdasarkan '/'
            return $url; // Mengembalikan array URL
        }
        return []; // Jika tidak ada URL, mengembalikan array kosong
    }
}

