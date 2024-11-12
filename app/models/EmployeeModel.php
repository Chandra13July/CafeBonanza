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

}