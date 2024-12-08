<?php

class Gallery extends Controller
{
    private $galleryModel;

    public function __construct()
    {
        // Initialize gallery model and check if the user is logged in
        $this->galleryModel = $this->model('GalleryModel');
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_message'] = 'You must log in first!';
            header('Location: ' . BASEURL . '/auth/loginAdmin');
            exit;
        }
    }

    public function index()
    {
        // Retrieve all gallery data and load the view
        $gallery = $this->galleryModel->getAllGallery();
        $this->view('layout/header');
        $this->view('layout/sidebar');
        $this->view('admin/gallery', ['gallery' => $gallery]);
    }

    public function btnAddGallery()
    {
        // Function to add a new gallery item
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);

            // Validate title and description
            if (empty($title) || empty($description)) {
                $_SESSION['error'] = "Title and description cannot be empty!";
                header("Location: " . BASEURL . "/gallery/index");
                exit();
            }

            // Upload image
            $imagePath = $this->uploadMedia();
            if ($imagePath === false) {
                $_SESSION['error'] = "Failed to upload image. Please try again.";
                header("Location: " . BASEURL . "/gallery/index");
                exit();
            }

            // Data for the new gallery entry
            $data = [
                'title' => $title,
                'description' => $description,
                'imageUrl' => $imagePath
            ];

            // Add the gallery to the database
            if ($this->galleryModel->addGallery($data)) {
                $_SESSION['success'] = "Gallery successfully added!";
            } else {
                $_SESSION['error'] = "Failed to add gallery, please try again.";
            }

            // Redirect to gallery page
            header("Location: " . BASEURL . "/gallery/index");
            exit();
        }
    }

    private function uploadMedia()
    {
        // Function to upload media (image/video)
        if (isset($_FILES['imageUrl']) && $_FILES['imageUrl']['error'] === 0) {
            $fileName = $_FILES['imageUrl']['name'];
            $fileTmpName = $_FILES['imageUrl']['tmp_name'];
            $fileSize = $_FILES['imageUrl']['size'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Allowed file extensions for image and video
            $allowedImageExt = ['jpg', 'jpeg', 'png', 'gif'];
            $allowedVideoExt = ['mp4', 'avi', 'mov', 'mkv'];

            // Validate file extension and size
            if (in_array($fileExt, $allowedImageExt) || in_array($fileExt, $allowedVideoExt)) {
                if ($fileSize < 50000000) {
                    // Generate a unique file name
                    $newFileName = uniqid('', true) . '.' . $fileExt;

                    // Determine upload directory based on file type
                    if (in_array($fileExt, $allowedImageExt)) {
                        $uploadDir = 'upload/gallery/images/';
                    } elseif (in_array($fileExt, $allowedVideoExt)) {
                        $uploadDir = 'upload/gallery/videos/';
                    }

                    // Create the directory if it does not exist
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    // Save the uploaded file
                    $uploadPath = $uploadDir . $newFileName;
                    if (move_uploaded_file($fileTmpName, $uploadPath)) {
                        return $uploadPath;
                    }
                } else {
                    $_SESSION['error'] = "File size is too large (max 50MB).";
                    return false;
                }
            } else {
                $_SESSION['error'] = "File format not allowed.";
                return false;
            }
        } else {
            $_SESSION['error'] = "No file uploaded or an error occurred.";
            return false;
        }
    }

    public function btnEditGallery()
    {
        // Function to edit an existing gallery
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Validate Gallery ID
            if (empty($_POST['GalleryId'])) {
                $_SESSION['error'] = "Gallery ID not found!";
                header("Location: " . BASEURL . "/gallery/index");
                exit();
            }

            // Get gallery data by ID
            $menu = $this->galleryModel->findGalleryById($_POST['GalleryId']);
            if (!$menu) {
                $_SESSION['error'] = "Gallery not found!";
                header("Location: " . BASEURL . "/gallery/index");
                exit();
            }

            // Validate title and description input
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            if (empty($title) || empty($description)) {
                $_SESSION['error'] = "Title and description cannot be empty!";
                header("Location: " . BASEURL . "/gallery/index");
                exit();
            }

            // Prepare data for update
            $data = [
                'GalleryId' => $_POST['GalleryId'],
                'title' => $title,
                'description' => $description,
            ];

            // Upload image if provided
            if (!empty($_FILES['imageUrl']['name'])) {
                $imagePath = $this->uploadMedia();
                if ($imagePath === false) {
                    $_SESSION['error'] = "Failed to upload image. Please try again.";
                    header("Location: " . BASEURL . "/gallery/index");
                    exit();
                }
                $data['imageUrl'] = $imagePath;
            } else {
                $data['imageUrl'] = $menu['ImageUrl']; // Use the old image if no new image is provided
            }

            // Update gallery in the database
            if ($this->galleryModel->editGallery($data)) {
                $_SESSION['success'] = "Gallery successfully updated!";
            } else {
                $_SESSION['error'] = "Failed to update gallery.";
            }

            // Redirect to gallery page
            header("Location: " . BASEURL . "/gallery/index");
            exit();
        }
    }

    public function btnDeleteGallery()
    {
        // Function to delete a gallery
        if (isset($_POST['GalleryId'])) {
            $galleryId = $_POST['GalleryId'];

            // Delete the gallery by ID
            if ($this->galleryModel->deleteGallery($galleryId)) {
                $_SESSION['success'] = "Gallery successfully deleted!";
            } else {
                $_SESSION['error'] = "Failed to delete gallery, please try again.";
            }
        } else {
            $_SESSION['error'] = "Invalid Menu ID.";
        }

        // Redirect to gallery page
        header("Location: " . BASEURL . "/gallery/index");
        exit();
    }
}
