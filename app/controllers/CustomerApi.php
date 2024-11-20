<?php

// Mengimpor file konfigurasi dan koneksi database
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';

class CustomerApi
{
    private $db;

    public function __construct()
    {
        // Membuat instance database
        $this->db = new Database();
    }

    // Mendapatkan semua data customer
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

    // Mendapatkan data customer berdasarkan ID
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

    // Menambahkan data customer baru
    public function addCustomer($data)
    {
        $this->db->query("INSERT INTO customer (Username, Email, Phone, Password, Gender, DateOfBirth, Address, ImageUrl) 
                          VALUES (:username, :email, :phone, :password, :gender, :dob, :address, :imageUrl)");
        $this->db->bind(':username', $data['Username']);
        $this->db->bind(':email', $data['Email']);
        $this->db->bind(':phone', $data['Phone'] ?? null);
        $this->db->bind(':password', password_hash($data['Password'], PASSWORD_BCRYPT));
        $this->db->bind(':gender', $data['Gender'] ?? null);
        $this->db->bind(':dob', $data['DateOfBirth'] ?? null);
        $this->db->bind(':address', $data['Address'] ?? null);
        $this->db->bind(':imageUrl', $data['ImageUrl'] ?? null);

        if ($this->db->execute()) {
            echo json_encode(["message" => "Customer added successfully"]);
        } else {
            echo json_encode(["message" => "Failed to add customer"]);
        }
    }

    // Mengedit data customer berdasarkan ID
    public function updateCustomer($id, $data)
    {
        $this->db->query("UPDATE customer SET Username = :username, Email = :email, Phone = :phone, 
                          Password = :password, Gender = :gender, DateOfBirth = :dob, Address = :address, ImageUrl = :imageUrl 
                          WHERE CustomerId = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':username', $data['Username']);
        $this->db->bind(':email', $data['Email']);
        $this->db->bind(':phone', $data['Phone'] ?? null);
        $this->db->bind(':password', password_hash($data['Password'], PASSWORD_BCRYPT));
        $this->db->bind(':gender', $data['Gender'] ?? null);
        $this->db->bind(':dob', $data['DateOfBirth'] ?? null);
        $this->db->bind(':address', $data['Address'] ?? null);
        $this->db->bind(':imageUrl', $data['ImageUrl'] ?? null);

        if ($this->db->execute()) {
            echo json_encode(["message" => "Customer updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update customer"]);
        }
    }

    // Menghapus data customer berdasarkan ID
    public function deleteCustomer($id)
    {
        $this->db->query("DELETE FROM customer WHERE CustomerId = :id");
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            echo json_encode(["message" => "Customer deleted successfully"]);
        } else {
            echo json_encode(["message" => "Failed to delete customer"]);
        }
    }
}

// Menggunakan API dengan metode HTTP
$customerApi = new CustomerApi();

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $customerApi->getCustomerById($_GET['id']);
    } else {
        $customerApi->getAllCustomers();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $customerApi->addCustomer($data);
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
    echo json_encode(["message" => "Invalid request"]);
}
