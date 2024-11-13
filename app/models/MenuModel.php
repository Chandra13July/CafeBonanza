<?php

class MenuModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllMenu()
    {
        $this->db->query('SELECT MenuId, MenuName, Description, Price, Stock, Category, ImageUrl FROM menu');
        return $this->db->resultSet();
    }

    public function addMenu($data)
    {
        $this->db->query("INSERT INTO menu (MenuName, Description, Price, Stock, Category, ImageUrl) 
                            VALUES (:menuName, :description, :price, :stock, :category, :imageUrl)");

        $this->db->bind(':menuName', $data['menuName']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':imageUrl', $data['imageUrl']);

        return $this->db->execute();
    }

    public function editMenu($data)
    {
        // Query untuk update menu
        $query = "UPDATE menu SET 
                    MenuName = :menuName, 
                    Description = :description, 
                    Price = :price, 
                    Stock = :stock, 
                    Category = :category,
                    ImageUrl = :imageUrl
                  WHERE MenuId = :menuId";

        // Eksekusi query
        $this->db->query($query);

        // Bind parameter dengan nilai yang diterima
        $this->db->bind(':menuName', $data['menuName']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':imageUrl', $data['imageUrl']);
        $this->db->bind(':menuId', $data['menuId']);

        // Cek apakah query berhasil dieksekusi
        return $this->db->execute();
    }

    public function deleteMenu($menuId)
    {
        $this->db->query("DELETE FROM menu WHERE MenuId = :menuId");
        $this->db->bind(':menuId', $menuId);

        return $this->db->execute();
    }
}
