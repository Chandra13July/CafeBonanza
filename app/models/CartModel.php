<?php

class CartModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Menambahkan item ke keranjang
    public function addToCart($customerId, $menuId, $quantity)
    {
        try {
            // Memeriksa apakah item sudah ada di keranjang
            $stmt = $this->db->prepare("SELECT Quantity FROM cart WHERE CustomerId = :CustomerId AND MenuId = :MenuId");
            $stmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
            $stmt->bindParam(':MenuId', $menuId, PDO::PARAM_INT);
            $stmt->execute();

            $existingCartItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingCartItem) {
                // Jika sudah ada, tambahkan jumlah item
                $newQuantity = $existingCartItem['Quantity'] + $quantity;

                // Mengupdate jumlah item dalam keranjang
                $updateStmt = $this->db->prepare("
                    UPDATE cart 
                    SET Quantity = :Quantity 
                    WHERE CustomerId = :CustomerId AND MenuId = :MenuId
                ");
                $updateStmt->bindParam(':Quantity', $newQuantity, PDO::PARAM_INT);
                $updateStmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
                $updateStmt->bindParam(':MenuId', $menuId, PDO::PARAM_INT);
                $updateStmt->execute();
            } else {
                // Jika belum ada, tambahkan item baru
                $insertStmt = $this->db->prepare("
                    INSERT INTO cart (CustomerId, MenuId, Quantity) 
                    VALUES (:CustomerId, :MenuId, :Quantity)
                ");
                $insertStmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
                $insertStmt->bindParam(':MenuId', $menuId, PDO::PARAM_INT);
                $insertStmt->bindParam(':Quantity', $quantity, PDO::PARAM_INT);
                $insertStmt->execute();
            }

            return true;
        } catch (PDOException $e) {
            error_log("Error in addToCart: " . $e->getMessage());
            return false;
        }
    }

    public function getMenuById($menuId)
    {
        $stmt = $this->db->prepare("SELECT * FROM menu WHERE MenuId = :MenuId");
        $stmt->bindParam(':MenuId', $menuId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCart($customerId)
    {
        $stmt = $this->db->prepare("SELECT 
            c.CartId,
            m.ImageUrl,
            m.MenuName,
            m.Description,
            m.Price,
            m.Stock,
            c.Quantity,
            (m.Price * c.Quantity) AS TotalPrice,
            c.CreatedAt
        FROM 
            cart c
        JOIN 
            menu m ON c.MenuId = m.MenuId
        WHERE 
            c.CustomerId = :CustomerId
        ORDER BY 
            c.CreatedAt");
        $stmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
