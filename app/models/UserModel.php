<?php

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database(); // Inisialisasi koneksi database
    }

    // Mengambil data pengguna berdasarkan username dari tabel cafe.customer
    public function getUserByUsername($username)
    {
        // Query untuk mendapatkan data pengguna berdasarkan username dari tabel cafe.customer
        $this->db->query("SELECT username, email, phone, gender, DateOfBirth AS dob, Address AS address, ImageUrl FROM cafe.customer WHERE username = :username");
        $this->db->bind(':username', $username);
        
        // Menjalankan query dan mengembalikan hasilnya
        return $this->db->single(); // Mengembalikan data pengguna satu baris
    }
}
