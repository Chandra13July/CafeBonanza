<?php

class Contact extends Controller
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = $this->model('ContactModel');

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'Anda harus login terlebih dahulu!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        $contacts = $this->contactModel->getAllContacts();

        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/contact', ['contacts' => $contacts]);
    }

    public function btnDeleteContact()
    {
        if (isset($_POST['ContactId'])) {
            $contactId = $_POST['ContactId'];

            if ($this->contactModel->deleteContact($contactId)) {
                $_SESSION['success'] = "Kontak berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus kontak, silakan coba lagi.";
            }
        } else {
            $_SESSION['error'] = "ID Kontak tidak valid.";
        }

        header("Location: " . BASEURL . "/contact/index");
        exit();
    }
}
