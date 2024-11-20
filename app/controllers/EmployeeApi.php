<?php

// Mengimpor file konfigurasi dan koneksi database
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';

class EmployeeApi
{
    private $db;

    public function __construct()
    {
        // Membuat instance database
        $this->db = new Database();
    }

    // Mendapatkan semua data karyawan
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

    // Mendapatkan data karyawan berdasarkan ID
    public function getEmployeeById($id)
    {
        $this->db->query("SELECT * FROM employee WHERE EmployeeId = :id");
        $this->db->bind(':id', $id);
        $employee = $this->db->single();

        echo json_encode($employee);
    }

    // Menambahkan data karyawan baru
    public function addEmployee($data)
    {
        $this->db->query("INSERT INTO employee (Username, Email, Phone, Role, Password, Gender, DateOfBirth, Address, ImageUrl) 
                          VALUES (:username, :email, :phone, :role, :password, :gender, :dob, :address, :imageUrl)");
        $this->db->bind(':username', $data['Username']);
        $this->db->bind(':email', $data['Email']);
        $this->db->bind(':phone', $data['Phone']);
        $this->db->bind(':role', $data['Role']);
        $this->db->bind(':password', $data['Password']);
        $this->db->bind(':gender', $data['Gender']);
        $this->db->bind(':dob', $data['DateOfBirth']);
        $this->db->bind(':address', $data['Address']);
        $this->db->bind(':imageUrl', $data['ImageUrl']);

        if ($this->db->execute()) {
            echo json_encode(["message" => "Employee added successfully"]);
        } else {
            echo json_encode(["message" => "Failed to add employee"]);
        }
    }

    // Mengedit data karyawan berdasarkan ID
    public function updateEmployee($id, $data)
    {
        $this->db->query("UPDATE employee SET Username = :username, Email = :email, Phone = :phone, Role = :role, 
                          Password = :password, Gender = :gender, DateOfBirth = :dob, Address = :address, ImageUrl = :imageUrl 
                          WHERE EmployeeId = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':username', $data['Username']);
        $this->db->bind(':email', $data['Email']);
        $this->db->bind(':phone', $data['Phone']);
        $this->db->bind(':role', $data['Role']);
        $this->db->bind(':password', $data['Password']);
        $this->db->bind(':gender', $data['Gender']);
        $this->db->bind(':dob', $data['DateOfBirth']);
        $this->db->bind(':address', $data['Address']);
        $this->db->bind(':imageUrl', $data['ImageUrl']);

        if ($this->db->execute()) {
            echo json_encode(["message" => "Employee updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update employee"]);
        }
    }

    // Menghapus data karyawan berdasarkan ID
    public function deleteEmployee($id)
    {
        $this->db->query("DELETE FROM employee WHERE EmployeeId = :id");
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            echo json_encode(["message" => "Employee deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete employee"]);
        }
    }
}

// Menggunakan API dengan metode HTTP
$employeeApi = new EmployeeApi();

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $employeeApi->getEmployeeById($_GET['id']);
    } else {
        $employeeApi->getAllEmployees();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $employeeApi->addEmployee($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($_GET['id'])) {
        $employeeApi->updateEmployee($_GET['id'], $data);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        $employeeApi->deleteEmployee($_GET['id']);
    }
} else {
    echo json_encode(["message" => "Invalid request"]);
}
