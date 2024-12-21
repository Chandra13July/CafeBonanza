<?php

class App
{

    protected $controller = 'Home'; // Default controller
    protected $method = 'index';    // Default method
    protected $params = [];         // Default params

    public function __construct()
    {
        $url = $this->parseURL();

        // Cek apakah $url valid dan file controller ada
        if (!empty($url) && file_exists(__DIR__ . '/../controllers/' . ucfirst($url[0]) . '.php')) {
            $this->controller = ucfirst($url[0]); // Pastikan nama controller diawali huruf besar
            unset($url[0]);
        }

        // Require file controller
        $controllerPath = __DIR__ . '/../controllers/' . $this->controller . '.php';
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
        } else {
            die("Controller file not found: " . $controllerPath);
        }

        // Instantiate controller
        if (class_exists($this->controller)) {
            $this->controller = new $this->controller;
        } else {
            die("Controller class not found: " . $this->controller);
        }

        // Cek apakah method valid
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // Atur parameter (jika ada)
        $this->params = !empty($url) ? array_values($url) : [];

        // Jalankan controller & method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/'); // Hapus '/' di akhir URL
            $url = filter_var($url, FILTER_SANITIZE_URL); // Sanitasi URL
            $url = explode('/', $url); // Pecah URL menjadi array
            return $url;
        }
        return []; // Kembalikan array kosong jika tidak ada 'url' di $_GET
    }
}
