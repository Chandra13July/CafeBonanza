<?php

class Database
{
    private $host = DB_HOST; // Host database
    private $user = DB_USER; // Username database
    private $pass = DB_PASS; // Password database
    private $db_name = DB_NAME; // Nama database

    private $dbh;  // Database handler
    private $stmt; // Statement handler

    public function __construct()
    {
        // Menyiapkan data source name (DSN)
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;

        // Opsi untuk koneksi PDO
        $option = [
            PDO::ATTR_PERSISTENT => true, // Menggunakan koneksi yang persisten
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Mode error sebagai exception
        ];

        try {
            // Membuat koneksi ke database menggunakan PDO
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $option);
        } catch (PDOException $e) {
            // Menangani error koneksi
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function prepare($query)
    {
        // Menyiapkan query SQL
        $this->stmt = $this->dbh->prepare($query);
        return $this->stmt;
    }

    public function query($query)
    {
        // Menyiapkan query SQL
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        // Mengikat parameter ke query dengan tipe data yang sesuai
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        // Menjalankan query yang telah disiapkan
        try {
            $this->stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error executing query: " . $e->getMessage();
            return false;
        }
    }

    public function resultSet()
    {
        // Mendapatkan semua hasil dari query dalam bentuk array asosiatif
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        // Mendapatkan satu baris hasil query dalam bentuk array asosiatif
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        // Mendapatkan jumlah baris yang terpengaruh oleh query
        return $this->stmt->rowCount();
    }
}
