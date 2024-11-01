<?php

class ContactModel
{
    private $table = 'contact'; // Nama tabel
    private $db;

    public function __construct()
    {
        $this->db = new Database; // Inisialisasi koneksi database
    }

    // Menambahkan kontak baru
    public function addContact($data)
    {
        $query = "INSERT INTO " . $this->table . " (Name, Email, Phone, Message)
                  VALUES (:name, :email, :phone, :message)";

        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('phone', $data['phone']);
        $this->db->bind('message', $data['message']);
        $this->db->execute();

        return $this->db->rowCount(); // Mengembalikan jumlah baris yang terpengaruh
    }
}
?>
