<?php

class Contact extends Controller
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = $this->model('ContactModel');

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'You must log in first!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        // Retrieve all contacts
        $contacts = $this->contactModel->getAllContacts();

        // Load views
        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/contact', ['contacts' => $contacts]);
    }

    public function btnDeleteContact()
    {
        // Function to delete a contact
        if (isset($_POST['ContactId'])) {
            $contactId = $_POST['ContactId'];

            // Delete the contact by ID
            if ($this->contactModel->deleteContact($contactId)) {
                $_SESSION['success'] = "Contact successfully deleted!";
            } else {
                $_SESSION['error'] = "Failed to delete contact, please try again.";
            }
        } else {
            $_SESSION['error'] = "Invalid Contact ID.";
        }

        // Redirect to the contact page
        header("Location: " . BASEURL . "/contact/index");
        exit();
    }
}
