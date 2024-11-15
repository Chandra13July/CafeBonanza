<?php

class Gallery extends Controller
{
    private $galleryModel;

    public function __construct()
    {
        $this->galleryModel = $this->model('GalleryModel');
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        $gallery = $this->galleryModel->getAllGallery();

        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/gallery', ['gallery' => $gallery]);
    }

    public function btnAddGallery()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $imagePath = $this->uploadImage();
  
            if ($imagePath === false) {
                header("Location: " . BASEURL . "/gallery/index");
                exit();
            }

            $data = [
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
                'imageUrl' => $imagePath 
            ];

            if ($this->galleryModel->addGallery ($data)) {
                $_SESSION['success'] = "Gallry berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Penambahan gallry gagal, silakan coba lagi.";
            }
            header("Location: " . BASEURL . "/gallery/index");
            exit();
        }
    }

    private function uploadImage()
    {
        if (isset($_FILES['imageUrl']) && $_FILES['imageUrl']['error'] === 0) {
            $imageName = $_FILES['imageUrl']['name'];
            $imageTmpName = $_FILES['imageUrl']['tmp_name'];
            $imageSize = $_FILES['imageUrl']['size'];
            $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageExt, $allowed)) {
                if ($imageSize < 5000000) { // Batas ukuran 5MB
                    $newImageName = uniqid('', true) . '.' . $imageExt;
                    $imageUploadPath = 'upload/gallery/' . $newImageName;

                    if (move_uploaded_file($imageTmpName, $imageUploadPath)) {
                        return $imageUploadPath; // Berhasil upload, return path
                    } else {
                        $_SESSION['error'] = "Gagal mengunggah gambar.";
                        return false;
                    }
                } else {
                    $_SESSION['error'] = "Ukuran gambar terlalu besar.";
                    return false;
                }
            } else {
                $_SESSION['error'] = "Jenis file gambar tidak valid.";
                return false;
            }
        }
        return null;
    }

    public function btnEditGallery()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $menu = $this->galleryModel->findGalleryById($_POST['GalleryId']); 

            $data = [
                'GalleryId' => $_POST['GalleryId'], 
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description']),
            ];

            if (!empty($_FILES['imageUrl']['name'])) {
                $data['imageUrl'] = $this->uploadImage();
            } else {
                $data['imageUrl'] = $menu['ImageUrl'];
            }

            if ($this->galleryModel->editGallery($data)) {
                $_SESSION['success'] = "Gallery berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Pembaharuan gallery gagal.";
            }

            header("Location: " . BASEURL . "/gallery/index");
            exit();
        }
    }

    public function btnDeleteGallery()
    {
        if (isset($_POST['GalleryId'])) {
            $galleryId = $_POST['GalleryId'];

            if ($this->galleryModel->deleteGallery($galleryId)) {
                $_SESSION['success'] = "Gallery berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus gallery, silakan coba lagi.";
            }
        } else {
            $_SESSION['error'] = "Menu ID tidak valid.";
        }

        header("Location: " . BASEURL . "/gallery/index");
        exit();
    }
}
