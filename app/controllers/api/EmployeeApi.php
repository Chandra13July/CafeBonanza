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

    public function login($data)
    {
        if (empty($data['Email']) || empty($data['Password'])) {
            echo json_encode(["status" => "error", "message" => "Email and password are required"]);
            return;
        }

        if (!filter_var($data['Email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["status" => "error", "message" => "Invalid email format"]);
            return;
        }

        $this->db->query("SELECT * FROM employee WHERE Email = :email");
        $this->db->bind(':email', $data['Email']);
        $employee = $this->db->single();

        if ($employee) {
            if (password_verify($data['Password'], $employee['Password'])) {
                $_SESSION['employee_id'] = $employee['EmployeeId'];
                $_SESSION['username'] = $employee['Username'];
                $_SESSION['email'] = $employee['Email'];
                $_SESSION['role'] = $employee['Role'];

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
                echo json_encode(["status" => "error", "message" => "Incorrect password. Please try again."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Email not registered. Please check your email or sign up."]);
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
        $this->db->bind(':imageUrl', $data['ImageUrl']);

        if ($this->db->execute()) {
            echo json_encode(["status" => "success", "message" => "Employee updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update employee"]);
        }
    }

    public function deleteEmployee($id)
    {
        $this->db->query("DELETE FROM employee WHERE EmployeeId = :id");
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            echo json_encode(["status" => "success", "message" => "Employee deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete employee"]);
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
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        $employeeApi->deleteEmployee($_GET['id']);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
