<?php

class EmployeeModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM employee WHERE Email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function getAllEmployee()
    {
        $this->db->query('SELECT EmployeeId, Username, Email, Phone, Gender, DateOfBirth, Address, Role, ImageUrl FROM employee');
        return $this->db->resultSet();
    }

    public function addEmployee($data)
    {
        $this->db->query("INSERT INTO employee (Username, Email, Phone, Password, Gender, DateOfBirth, Address, Role, ImageUrl) 
                            VALUES (:username, :email, :phone, :password, :gender, :dateOfBirth, :address, :role, :imageUrl)");

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':dateOfBirth', $data['dateOfBirth']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':imageUrl', $data['imageUrl']);

        return $this->db->execute();
    }

    public function editEmployee($data)
    {
        $query = "UPDATE employee SET 
                    Username = :username, 
                    Email = :email, 
                    Phone = :phone, 
                    Gender = :gender, 
                    DateOfBirth = :dateOfBirth, 
                    Address = :address, 
                    Role = :role,
                    ImageUrl = :imageUrl";

        if (isset($data['password']) && !empty($data['password'])) {
            $query .= ", Password = :password";
        }

        $query .= " WHERE EmployeeId = :EmployeeId";

        $this->db->query($query);

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':dateOfBirth', $data['dateOfBirth']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':imageUrl', $data['imageUrl']);
        $this->db->bind(':EmployeeId', $data['EmployeeId']); // Perbaikan penamaan bind

        if (isset($data['password']) && !empty($data['password'])) {
            $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        }

        return $this->db->execute();
    }

    public function deleteEmployee($employeeId)
    {
        $this->db->query("DELETE FROM employee WHERE EmployeeId = :EmployeeId");
        $this->db->bind(':EmployeeId', $employeeId);

        return $this->db->execute();
    }
}
