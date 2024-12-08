<?php

class ContactModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllContacts()
    {
        $this->db->query('SELECT ContactId, Name, Email, Type, Message FROM contact');
        return $this->db->resultSet();
    }

    public function addContact($data)
    {
        $this->db->query("INSERT INTO contact (Name, Email, Type, Message) 
                          VALUES (:name, :email, :type, :message)");

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':message', $data['message']);
        return $this->db->execute();
    }

    public function deleteContact($contactId)
    {
        $this->db->query("DELETE FROM contact WHERE ContactId = :ContactId");
        $this->db->bind(':ContactId', $contactId);
        return $this->db->execute();
    }

    public function getLatestContacts()
{
    $query = 'SELECT ContactId, Name, Message 
              FROM contact 
              ORDER BY CreatedAt DESC 
              LIMIT 100';

    $stmt = $this->db->prepare($query);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}

}
