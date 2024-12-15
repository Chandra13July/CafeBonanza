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
                OrderDetail od ON m.MenuId = od.MenuId
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

    public function getPopularMenu($limit = 5)
    {
        $query = "
            SELECT 
                m.MenuName,
                m.Description,
                m.ImageUrl,
                SUM(od.Quantity) AS totalQuantity
            FROM `orderdetail` od
            JOIN `menu` m ON od.MenuId = m.MenuId
            GROUP BY m.MenuId
            ORDER BY totalQuantity DESC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockStatus($threshold = 5)
    {
        $query = "
        SELECT 
            m.MenuName,
            m.Description,
            m.ImageUrl,
            m.Stock
        FROM `menu` m
        WHERE m.Stock = 0  -- Menampilkan menu dengan stok habis
        OR m.Stock <= :threshold  -- Atau stok hampir habis
        ORDER BY m.MenuName
    ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':threshold', $threshold, PDO::PARAM_INT);  // Menambahkan batas stok hampir habis
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function donutChartCategoryOrder()
    {
        $query = "
    SELECT 
        m.Category,  
        SUM(od.Quantity) AS totalSold  
    FROM `order` o
    JOIN `orderdetail` od ON o.OrderId = od.OrderId  
    JOIN `menu` m ON od.MenuId = m.MenuId  
    GROUP BY m.Category;
    ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        $totalSold = [];

        foreach ($result as $row) {
            $categories[] = $row['Category'];
            $totalSold[] = $row['totalSold'];
        }

        return [
            'categories' => $categories,
            'totalSold' => $totalSold
        ];
    }
}
