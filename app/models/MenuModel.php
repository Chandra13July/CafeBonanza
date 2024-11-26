<?php

class MenuModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findMenuById($id)
    {
        $this->db->query('SELECT * FROM menu WHERE MenuId = :MenuId');
        $this->db->bind(':MenuId', $id);
        return $this->db->single();
    }

    public function getAllMenu()
    {
        $this->db->query('SELECT MenuId, MenuName, Description, Price, Stock, Category, ImageUrl FROM menu ORDER BY CreatedAt DESC');
        return $this->db->resultSet();
    }

    public function getMenu()
    {
        $this->db->query('
            SELECT 
                m.MenuId, 
                m.MenuName, 
                m.Description, 
                m.Price, 
                m.Stock, 
                m.Category, 
                m.ImageUrl, 
                COALESCE(SUM(od.Quantity), 0) AS TotalSold
            FROM 
                menu m
            LEFT JOIN 
                OrderDetails od ON m.MenuId = od.MenuId
            GROUP BY 
                m.MenuId, m.MenuName, m.Description, m.Price, m.Stock, m.Category, m.ImageUrl
        ');

        return $this->db->resultSet();
    }


    public function getMenuById($menuId)
    {
        $this->db->query("SELECT * FROM menu WHERE MenuId = :menuId");
        $this->db->bind(':menuId', $menuId);
        return $this->db->single();
    }

    public function getTotalMenu()
    {
        $this->db->query("SELECT COUNT(MenuId) AS totalMenu FROM menu");
        return $this->db->single()['totalMenu'];
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
        $query = "UPDATE menu SET 
                    MenuName = :menuName, 
                    Description = :description, 
                    Price = :price, 
                    Stock = :stock, 
                    Category = :category,
                    ImageUrl = :imageUrl 
                    WHERE MenuId = :MenuId";

        $this->db->query($query);
        $this->db->bind(':menuName', $data['menuName']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':imageUrl', $data['imageUrl']);
        $this->db->bind(':MenuId', $data['MenuId']);

        return $this->db->execute();
    }

    public function deleteMenu($menuId)
    {
        $this->db->query("DELETE FROM menu WHERE MenuId = :MenuId");
        $this->db->bind(':MenuId', $menuId);

        return $this->db->execute();
    }
}
