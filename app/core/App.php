<?php 

class App {
    protected $controller = 'Home';
    protected $method = 'home';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // Cek apakah $url ada dan berisi elemen
        if ($url && file_exists('../app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // method
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // params
        $this->params = $url ? array_values($url) : [];

        // jalankan controller & method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return []; // Mengembalikan array kosong jika tidak ada 'url' di $_GET
    }
}
