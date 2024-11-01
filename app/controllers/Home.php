<?php

class Home extends Controller
{
    public function home()
    {
        $this->view('templates/header');
        $this->view('templates/navbar');
        $this->view('home/home');
        $this->view('templates/footer');
    }

    public function gallery()
    {
        $this->view('templates/header');
        $this->view('templates/navbar');
        $this->view('home/gallery');
        $this->view('templates/footer');
    }

    public function contact()
    {
        $this->view('templates/header');
        $this->view('templates/navbar');
        $this->view('home/contact');
        $this->view('templates/footer');
    }

    public function btnContact()
    {
        if ($this->model('ContactModel')->addContact($_POST) > 0) {
            $_SESSION['flash'] = 'Pesan berhasil dikirim!';
            header('Location: ' . BASEURL . '/home/contact');
            exit;
        } else {
            $_SESSION['flash'] = 'Pesan gagal dikirim, coba lagi.';
            header('Location: ' . BASEURL . '/home/contact');
            exit;
        }
    }
}
