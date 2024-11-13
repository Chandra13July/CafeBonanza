<?php
require_once '../config/config.php';
require_once '../core/Database.php';

class EmployeeApi
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Get all employees
    public function getEmployees()
    {
        $this->db->query("SELECT * FROM employee");
        $employees = $this->db->resultSet();
        echo json_encode($employees);
    }

    // Get employee by ID
    public function getEmployeeById($id)
    {
        $this->db->query("SELECT * FROM employee WHERE EmployeeId = :id");
        $this->db->bind(':id', $id);
        $employee = $this->db->single();
        echo "data" . json_encode($employee);
    }

    // Add new employee
    public function addEmployee($data)
    {
        $this->db->query("INSERT INTO employee (Username, Email, Phone, Role, Password, Gender, DateOfBirth, Address, ImageUrl) 
                          VALUES (:username, :email, :phone, :role, :password, :gender, :dateOfBirth, :address, :imageUrl)");
        $this->db->bind(':username', $data['Username']);
        $this->db->bind(':email', $data['Email']);
        $this->db->bind(':phone', $data['Phone']);
        $this->db->bind(':role', $data['Role']);
        $this->db->bind(':password', password_hash($data['Password'], PASSWORD_BCRYPT)); // Encrypt password
        $this->db->bind(':gender', $data['Gender']);
        $this->db->bind(':dateOfBirth', $data['DateOfBirth']);
        $this->db->bind(':address', $data['Address']);
        $this->db->bind(':imageUrl', $data['ImageUrl']);

        if ($this->db->execute()) {
            echo json_encode(["message" => "Employee added successfully"]);
        } else {
            echo json_encode(["message" => "Failed to add employee"]);
        }
    }

    // Update employee
    public function updateEmployee($id, $data)
    {
        $this->db->query("UPDATE employee SET 
                          Username = :username, Email = :email, Phone = :phone, Role = :role, 
                          Password = :password, Gender = :gender, DateOfBirth = :dateOfBirth, 
                          Address = :address, ImageUrl = :imageUrl WHERE EmployeeId = :id");
        $this->db->bind(':username', $data['Username']);
        $this->db->bind(':email', $data['Email']);
        $this->db->bind(':phone', $data['Phone']);
        $this->db->bind(':role', $data['Role']);
        $this->db->bind(':password', password_hash($data['Password'], PASSWORD_BCRYPT)); // Encrypt password
        $this->db->bind(':gender', $data['Gender']);
        $this->db->bind(':dateOfBirth', $data['DateOfBirth']);
        $this->db->bind(':address', $data['Address']);
        $this->db->bind(':imageUrl', $data['ImageUrl']);
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            echo json_encode(["message" => "Employee updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update employee"]);
        }
    }

    // Delete employee
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

// Handle requests
$employeeApi = new EmployeeApi();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

switch ($requestMethod) {
    case 'GET':
        if ($id) {
            $employeeApi->getEmployeeById($id);
        } else {
            $employeeApi->getEmployees();
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $employeeApi->addEmployee($data);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $employeeApi->updateEmployee($id, $data);
        break;

    case 'DELETE':
        $employeeApi->deleteEmployee($id);
        break;

    default:
        echo json_encode(["message" => "Request method not supported"]);
        break;
}
