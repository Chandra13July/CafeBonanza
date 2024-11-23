<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../core/Database.php';

class CustomerApi
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        session_start();
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['email']) || empty($data['password'])) {
            echo json_encode(["message" => "Email and password must be filled!"]);
            return;
        }

        $email = trim($data['email']);
        $password = $data['password'];

        $this->db->query("SELECT * FROM customer WHERE Email = :email");
        $this->db->bind(':email', $email);
        $user = $this->db->single();

        if (!$user) {
            echo json_encode(["message" => "Email is not registered!"]);
            return;
        }

        if (!password_verify($password, $user['Password'])) {
            echo json_encode(["message" => "Incorrect password!"]);
            return;
        }

        $_SESSION['user_id'] = $user['CustomerId'];
        $_SESSION['username'] = $user['Username'];
        $_SESSION['imageUrl'] = $user['ImageUrl'] ?? BASEURL . '/img/user.png';

        echo json_encode([
            "message" => $user['Username'] . " logged in successfully!",
            "user" => [
                "CustomerId" => $user['CustomerId'],
                "Username" => $user['Username'],
                "Email" => $user['Email'],
                "Phone" => $user['Phone'],
                "Gender" => $user['Gender'],
                "DateOfBirth" => $user['DateOfBirth'],
                "Address" => $user['Address'],
                "ImageUrl" => $user['ImageUrl'] ? BASEURL . '/' . $user['ImageUrl'] : null,
            ]
        ]);
    }

    public function getAllCustomers()
    {
        $this->db->query("SELECT * FROM customer");
        $customers = $this->db->resultSet();

        $data = ["data" => []];
        foreach ($customers as $customer) {
            $data_customer = [
                "CustomerId" => $customer["CustomerId"],
                "Username" => $customer["Username"],
                "Email" => $customer["Email"],
                "Phone" => $customer["Phone"],
                "Gender" => $customer["Gender"],
                "DateOfBirth" => $customer["DateOfBirth"],
                "Address" => $customer["Address"],
                "ImageUrl" => $customer["ImageUrl"] ? BASEURL . '/' . $customer["ImageUrl"] : null,
                "CreatedAt" => $customer["CreatedAt"],
            ];
            array_push($data['data'], $data_customer);
        }
        echo json_encode($data);
    }

    public function getCustomerById($id)
    {
        $this->db->query("SELECT * FROM customer WHERE CustomerId = :id");
        $this->db->bind(':id', $id);
        $customer = $this->db->single();

        if ($customer) {
            $customer['ImageUrl'] = $customer['ImageUrl'] ? BASEURL . '/' . $customer['ImageUrl'] : null;
            echo json_encode($customer);
        } else {
            echo json_encode(["message" => "Customer not found"]);
        }
    }

    public function addCustomer($data)
    {
        if (empty($data['Username']) || empty($data['Email']) || empty($data['Password']) || empty($data['Gender'])) {
            echo json_encode(["status" => "error", "message" => "All fields are required"]);
            return;
        }

        $hashedPassword = password_hash($data['Password'], PASSWORD_BCRYPT);

        $this->db->query("INSERT INTO customer (Username, Email, Phone, Password, Gender, DateOfBirth, Address, ImageUrl) 
                          VALUES (:username, :email, :phone, :password, :gender, :dob, :address, :imageUrl)");

        $this->db->bind(':username', $data['Username']);
        $this->db->bind(':email', $data['Email']);
        $this->db->bind(':phone', $data['Phone'] ?? null);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':gender', $data['Gender']);
        $this->db->bind(':dob', $data['DateOfBirth'] ?? null);
        $this->db->bind(':address', $data['Address'] ?? null);
        $this->db->bind(':imageUrl', $data['ImageUrl'] ?? null);

        if ($this->db->execute()) {
            echo json_encode(["status" => "success", "message" => "Customer added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to add customer"]);
        }
    }

    public function updateCustomer($id, $data)
    {
        if (empty($data['Username']) || empty($data['Email'])) {
            echo json_encode(["status" => "error", "message" => "Username and Email are required"]);
            return;
        }

        $hashedPassword = isset($data['Password']) ? password_hash($data['Password'], PASSWORD_BCRYPT) : null;

        $this->db->query("UPDATE customer SET 
                          Username = :username, 
                          Email = :email, 
                          Phone = :phone, 
                          Gender = :gender, 
                          DateOfBirth = :dob, 
                          Address = :address, 
                          ImageUrl = :imageUrl, 
                          Password = :password
                          WHERE CustomerId = :id");

        $this->db->bind(':id', $id);
        $this->db->bind(':username', $data['Username']);
        $this->db->bind(':email', $data['Email']);
        $this->db->bind(':phone', $data['Phone'] ?? null);
        $this->db->bind(':gender', $data['Gender'] ?? null);
        $this->db->bind(':dob', $data['DateOfBirth'] ?? null);
        $this->db->bind(':address', $data['Address'] ?? null);
        $this->db->bind(':imageUrl', $data['ImageUrl'] ?? null);
        $this->db->bind(':password', $hashedPassword);

        if ($this->db->execute()) {
            echo json_encode(["status" => "success", "message" => "Customer updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update customer"]);
        }
    }

    public function deleteCustomer($id)
    {
        $this->db->query("DELETE FROM customer WHERE CustomerId = :id");
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            echo json_encode(["status" => "success", "message" => "Customer deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete customer"]);
        }
    }
}


$customerApi = new CustomerApi();

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'login':
                $customerApi->login();
                break;

            default:
                $data = json_decode(file_get_contents("php://input"), true);
                $customerApi->addCustomer($data);
                break;
        }
    } else {
        $data = json_decode(file_get_contents("php://input"), true);
        $customerApi->addCustomer($data); 
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($_GET['id'])) {
        $customerApi->updateCustomer($_GET['id'], $data);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        $customerApi->deleteCustomer($_GET['id']);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
