<?php

class CustomerModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findUserById($id)
    {
        $this->db->query('SELECT * FROM customer WHERE CustomerId = :CustomerId');
        $this->db->bind(':CustomerId', $id);
        return $this->db->single();
    }

    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM customer WHERE Email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function resetPassword($email, $newPassword)
    {
        $this->db->query('UPDATE customer SET Password = :password WHERE Email = :email');
        $this->db->bind(':password', password_hash($newPassword, PASSWORD_DEFAULT));
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    public function getAllCustomer()
    {
        $this->db->query('SELECT CustomerId, Username, Email, Phone, Gender, DateOfBirth, Address, ImageUrl FROM customer');
        return $this->db->resultSet();
    }

    public function getTotalCustomer()
    {
        $this->db->query("SELECT COUNT(CustomerId) AS totalCustomer FROM customer");
        return $this->db->single()['totalCustomer'];
    }

    public function getUserByUsername($username)
    {
        $this->db->query("SELECT username, email, phone, gender, DateOfBirth AS dob, Address AS address, ImageUrl, CreatedAt 
                          FROM customer 
                          WHERE username = :username");
        $this->db->bind(':username', $username);
        $result = $this->db->single();

        return $result;
    }

    public function addCustomer($data)
    {
        $this->db->query("INSERT INTO customer (Username, Email, Phone, Password, Gender, DateOfBirth, Address, ImageUrl) 
                            VALUES (:username, :email, :phone, :password, :gender, :dateOfBirth, :address, :imageUrl)");

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':dateOfBirth', $data['dateOfBirth']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':imageUrl', $data['imageUrl']);

        return $this->db->execute();
    }

    public function editCustomer($data)
    {
        $query = "UPDATE customer SET 
                    Username = :username, 
                    Email = :email, 
                    Phone = :phone, 
                    Gender = :gender, 
                    DateOfBirth = :dateOfBirth, 
                    Address = :address, 
                    ImageUrl = :imageUrl";

        if (isset($data['password']) && !empty($data['password'])) {
            $query .= ", Password = :password";
        }

        $query .= " WHERE CustomerId = :CustomerId";

        $this->db->query($query);

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':dateOfBirth', $data['dateOfBirth']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':imageUrl', $data['imageUrl']);
        $this->db->bind(':CustomerId', $data['CustomerId']);

        if (isset($data['password']) && !empty($data['password'])) {
            $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        }

        return $this->db->execute();
    }

    public function editProfile($data)
    {
        $this->db->query("UPDATE customer SET 
                        Username = :username, 
                        Email = :email, 
                        Phone = :phone, 
                        Gender = :gender, 
                        DateOfBirth = :dateOfBirth, 
                        Address = :address 
                      WHERE CustomerId = :CustomerId");

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':dateOfBirth', $data['dateOfBirth']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':CustomerId', $data['CustomerId']);

        return $this->db->execute();
    }

    public function editImage($data)
    {
        $this->db->query("UPDATE customer SET 
                        ImageUrl = :imageUrl 
                      WHERE CustomerId = :CustomerId");

        $this->db->bind(':imageUrl', $data['imageUrl']);
        $this->db->bind(':CustomerId', $data['CustomerId']);

        return $this->db->execute();
    }

    public function deleteCustomer($customerid)
    {
        $this->db->query("DELETE FROM customer WHERE CustomerId = :CustomerId");
        $this->db->bind(':CustomerId', $customerid);

        return $this->db->execute();
    }

    public function signup($data)
    {
        $this->db->query('INSERT INTO customer (Username, Email, Password) VALUES (:username, :email, :password)');
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        // Hash the password before storing it
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->bind(':password', $hashedPassword);
        return $this->db->execute();
    }

    public function getPopularCustomer($limit = 5)
{
    $query = "
        SELECT 
            c.Username,
            c.Email,
            c.ImageUrl,
            COUNT(DISTINCT o.OrderId) AS totalOrders
        FROM `orders` o
        JOIN `customer` c ON o.CustomerId = c.CustomerId
        GROUP BY o.CustomerId
        ORDER BY totalOrders DESC
        LIMIT :limit
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
