<?php
class CartModel 
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    // Menambahkan item ke cart dan simpan ke database
    public function addToCart($menuId, $menuName, $price, $quantity, $customerid) {
        // Mengecek apakah item sudah ada di database cart
        $stmt = $this->db->prepare("SELECT * FROM cart WHERE MenuId = :MenuId AND CustomerId = :CustomerId");
        $stmt->bindParam(':MenuId', $menuId);
        $stmt->bindParam(':CustomerId', $customerid);
        $stmt->execute();
        $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingItem) {
            // Jika item sudah ada, update quantity dan subtotal
            $newQuantity = $existingItem['Quantity'] + $quantity;
            $newSubTotal = $price * $newQuantity;

            $stmt = $this->db->prepare("UPDATE cart SET Quantity = :Quantity, SubTotal = :SubTotal WHERE CartId = :CartId");
            $stmt->bindParam(':Quantity', $newQuantity);
            $stmt->bindParam(':SubTotal', $newSubTotal);
            $stmt->bindParam(':CartId', $existingItem['CartId']);
            $stmt->execute();
        } else {
            // Jika item belum ada, tambahkan item baru ke cart
            $subTotal = $price * $quantity;

            $stmt = $this->db->prepare("INSERT INTO cart (MenuId, MenuName, Price, Quantity, SubTotal, CustomerId) 
                VALUES (:MenuId, :MenuName, :Price, :Quantity, :SubTotal, :CustomerId)");
            $stmt->bindParam(':MenuId', $menuId);
            $stmt->bindParam(':MenuName', $menuName);
            $stmt->bindParam(':Price', $price);
            $stmt->bindParam(':Quantity', $quantity);
            $stmt->bindParam(':SubTotal', $subTotal);
            $stmt->bindParam(':CustomerId', $customerid);  // Pastikan Anda menangkap UserId yang login
            $stmt->execute();
        }
    }

    // Mendapatkan cart berdasarkan UserId
    public function getCart($userId) {
        $stmt = $this->db->prepare("SELECT * FROM cart WHERE CustomerId = :CustomerId");
        $stmt->bindParam(':CustomerId', $customerid);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
