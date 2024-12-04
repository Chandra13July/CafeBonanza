<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../core/Database.php';

class EmployeeApi
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function login()
    {
        // Ambil data dari $_POST jika menggunakan Content-Type: application/x-www-form-urlencoded
        $data = [
            'Email' => $_POST['Email'] ?? null,
            'Password' => $_POST['Password'] ?? null,
        ];
    
        // Validasi data
        if (empty($data['Email']) || empty($data['Password'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Email and password are required"
            ]);
            return;
        }
    
        if (!filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid email format"
            ]);
            return;
        }
    
        // Query database untuk mendapatkan data karyawan
        $this->db->query("SELECT * FROM employee WHERE Email = :email");
        $this->db->bind(':email', $data['Email']);
        $employee = $this->db->single();
    
        // Validasi hasil query
        if ($employee) {
            // Verifikasi password
            if (password_verify($data['Password'], $employee['Password'])) {
                // Simpan data user ke session
                $_SESSION['employee_id'] = $employee['EmployeeId'];
                $_SESSION['username'] = $employee['Username'];
                $_SESSION['email'] = $employee['Email'];
                $_SESSION['role'] = $employee['Role'];
    
                // Kirim response JSON untuk login berhasil
                echo json_encode([
                    "status" => "success",
                    "message" => "Login successful. Welcome, " . $employee['Username'] . " (" . $employee['Role'] . ")!",
                    "user" => [
                        "EmployeeId" => $employee['EmployeeId'],
                        "Username" => $employee['Username'],
                        "Email" => $employee['Email'],
                        "Role" => $employee['Role'],
                        "Phone" => $employee['Phone'],
                        "Gender" => $employee['Gender'],
                        "DateOfBirth" => $employee['DateOfBirth'],
                        "Address" => $employee['Address'],
                        "ImageUrl" => $employee['ImageUrl'] ? BASEURL . '/' . $employee['ImageUrl'] : null,
                    ]
                ]);
            } else {
                // Password salah
                echo json_encode([
                    "status" => "error",
                    "message" => "Incorrect password. Please try again."
                ]);
            }
        } else {
            // Email tidak ditemukan
            echo json_encode([
                "status" => "error",
                "message" => "Email not registered. Please check your email or sign up."
            ]);
        }
    }

    public function getAllEmployees()
    {
        $this->db->query("SELECT * FROM employee");
        $employees = $this->db->resultSet();

        $data = ["data" => []];
        foreach ($employees as $employee) {
            $data_employee = [
                "EmployeeId" => $employee["EmployeeId"],
                "Username" => $employee["Username"],
                "Email" => $employee["Email"],
                "Phone" => $employee["Phone"],
                "Role" => $employee["Role"],
                "Gender" => $employee["Gender"],
                "DateOfBirth" => $employee["DateOfBirth"],
                "Address" => $employee["Address"],
                "ImageUrl" => BASEURL . '/' . $employee["ImageUrl"],
                "CreatedAt" => $employee["CreatedAt"],
            ];

            array_push($data['data'], $data_employee);
        }

        echo json_encode($data);
    }

    public function getEmployeeById($id)
    {
        $this->db->query("SELECT * FROM employee WHERE EmployeeId = :id");
        $this->db->bind(':id', $id);
        $employee = $this->db->single();

        echo json_encode($employee);
    }

    public function addEmployee($data)
    {
        // Validasi file gambar
        if (isset($_FILES['ImageUrl']) && $_FILES['ImageUrl']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['ImageUrl'];
            $targetDir = __DIR__ . '/../../uploads/';
            $imageName = uniqid() . '-' . basename($image['name']);
            $targetFilePath = $targetDir . $imageName;

            // Validasi tipe file
            $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $validExtensions)) {
                echo json_encode(["status" => "error", "message" => "Invalid image format. Allowed: jpg, jpeg, png, gif"]);
                return;
            }

            // Simpan file gambar ke direktori tujuan
            if (!move_uploaded_file($image['tmp_name'], $targetFilePath)) {
                echo json_encode(["status" => "error", "message" => "Failed to upload image"]);
                return;
            }

            $data['ImageUrl'] = 'uploads/' . $imageName;
        } else {
            $data['ImageUrl'] = null; // Gambar opsional
        }

        // Lanjutkan dengan penyimpanan data
        $this->db->query("INSERT INTO employee (Username, Email, Phone, Role, Password, Gender, DateOfBirth, Address, ImageUrl) 
                      VALUES (:username, :email, :phone, :role, :password, :gender, :dob, :address, :imageUrl)");
        $this->db->bind(':username', $data['Username']);
        $this->db->bind(':email', $data['Email']);
        $this->db->bind(':phone', $data['Phone']);
        $this->db->bind(':role', $data['Role']);
        $this->db->bind(':password', password_hash($data['Password'], PASSWORD_DEFAULT));
        $this->db->bind(':gender', $data['Gender']);
        $this->db->bind(':dob', $data['DateOfBirth']);
        $this->db->bind(':address', $data['Address']);
        $this->db->bind(':imageUrl', $data['ImageUrl']);

        if ($this->db->execute()) {
            echo json_encode(["status" => "success", "message" => "Employee added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add employee"]);
        }
    }

    public function updateEmployee($id, $data)
    {
        // Siapkan jalur gambar jika di-upload
        $imagePath = null;

        if (isset($_FILES['ImageUrl']) && $_FILES['ImageUrl']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['ImageUrl'];
            $targetDir = __DIR__ . '/../../uploads/';
            $imageName = uniqid() . '-' . basename($image['name']);
            $targetFilePath = $targetDir . $imageName;

            // Validasi tipe file
            $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $validExtensions)) {
                echo json_encode(["status" => "error", "message" => "Invalid image format. Allowed: jpg, jpeg, png, gif"]);
                return;
            }

            // Simpan file gambar
            if (!move_uploaded_file($image['tmp_name'], $targetFilePath)) {
                echo json_encode(["status" => "error", "message" => "Failed to upload image"]);
                return;
            }

            $imagePath = 'uploads/' . $imageName;
        } else {
            // Tidak ada file baru, gunakan data lama jika ada
            $imagePath = $data['ImageUrl'] ?? null;
        }

        // Update data di database
        $this->db->query("UPDATE employee SET Username = :username, Email = :email, Phone = :phone, Role = :role, 
                      Password = :password, Gender = :gender, DateOfBirth = :dob, Address = :address, ImageUrl = :imageUrl 
                      WHERE EmployeeId = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':username', $data['Username']);
        $this->db->bind(':email', $data['Email']);
        $this->db->bind(':phone', $data['Phone']);
        $this->db->bind(':role', $data['Role']);
        $this->db->bind(':password', password_hash($data['Password'], PASSWORD_DEFAULT));
        $this->db->bind(':gender', $data['Gender']);
        $this->db->bind(':dob', $data['DateOfBirth']);
        $this->db->bind(':address', $data['Address']);
        $this->db->bind(':imageUrl', $imagePath);

        if ($this->db->execute()) {
            echo json_encode(["status" => "success", "message" => "Employee updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update employee"]);
        }
    }


    public function uploadImage()
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(["status" => "error", "message" => "No file uploaded or upload error"]);
            return;
        }

        $file = $_FILES['image'];
        $targetDir = __DIR__ . '/../../uploads/';
        $fileName = uniqid() . '-' . basename($file['name']);
        $targetFilePath = $targetDir . $fileName;

        // Validasi file
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(["status" => "error", "message" => "Invalid file type"]);
            return;
        }

        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            echo json_encode([
                "status" => "success",
                "message" => "File uploaded successfully",
                "imageUrl" => "uploads/" . $fileName
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to upload file"]);
        }
    }
}
$employeeApi = new EmployeeApi();

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $employeeApi->getEmployeeById($_GET['id']);
    } else {
        $employeeApi->getAllEmployees();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && $_GET['action'] == 'login') {
        $data = json_decode(file_get_contents("php://input"), true);
        $employeeApi->login($data);
    } else {
        $data = json_decode(file_get_contents("php://input"), true);
        $employeeApi->addEmployee($data);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($_GET['id'])) {
        $employeeApi->updateEmployee($_GET['id'], $data);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
