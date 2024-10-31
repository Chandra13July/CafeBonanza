<?php 

class Home extends Controller {
    public function home()
    {
        $this->view('templates/header');
        $this->view('templates/navbar');
        $this->view('home/home');
        $this->view('templates/footer');
    }
}