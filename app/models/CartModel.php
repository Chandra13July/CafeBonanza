<?php
class CartModel 
{
    private $db;

    public function __construct()
    {
        $this->db = new Database(); // Assuming you have a Database class for PDO connections
    }

    public function addToCart($menuId, $quantity, $customerId) {
        // Ensure the quantity is an integer
        $quantity = (int)$quantity;
        
        // Check if the item already exists in the cart
        $stmt = $this->db->prepare("SELECT * FROM cart WHERE MenuId = :MenuId AND CustomerId = :CustomerId");
        $stmt->bindParam(':MenuId', $menuId);
        $stmt->bindParam(':CustomerId', $customerId);
        $stmt->execute();
        $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existingItem) {
            // If the item exists, update the quantity
            $newQuantity = $existingItem['Quantity'] + $quantity;
    
            $stmt = $this->db->prepare("UPDATE cart SET Quantity = :Quantity WHERE CartId = :CartId");
            $stmt->bindParam(':Quantity', $newQuantity, PDO::PARAM_INT);
            $stmt->bindParam(':CartId', $existingItem['CartId'], PDO::PARAM_INT);
            $stmt->execute();
        } else {
            // If the item does not exist, add it to the cart
            $stmt = $this->db->prepare("INSERT INTO cart (MenuId, Quantity, CustomerId) 
                VALUES (:MenuId, :Quantity, :CustomerId)");
            $stmt->bindParam(':MenuId', $menuId, PDO::PARAM_INT);
            $stmt->bindParam(':Quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':CustomerId', $customerId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }    

    // Get cart items based on UserId
    public function getCart($customerId) {
        $stmt = $this->db->prepare("SELECT 
                c.CartId,
                m.MenuName,
                m.Price,
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
        $stmt->bindParam(':CustomerId', $customerId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
