<?php

class MenuCustModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getMenu($category = null)
    {
        // Set the base query
        $query = 'SELECT MenuId, MenuName, Description, Price, Stock, Category, ImageUrl FROM menu';

        // Add WHERE clause if category filter is provided
        if (!empty($category)) {
            $query .= ' WHERE Category = :category';
        }
        
        // Prepare the query
        $this->db->query($query);
        
        // Bind the category parameter if provided
        if (!empty($category)) {
            $this->db->bind(':category', $category);
        }
        
        // Execute and return the result set
        return $this->db->resultSet();
    }

    // Method to update stock after purchase
    public function updateStock($menuId, $quantity)
    {
        // Query to update the stock by decreasing the quantity
        $query = 'UPDATE menu SET Stock = Stock - :quantity WHERE MenuId = :menuId AND Stock >= :quantity';

        // Prepare the query
        $this->db->query($query);

        // Bind the parameters
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':menuId', $menuId);

        // Execute the query and return true if the update was successful, otherwise false
        return $this->db->execute();
    }

    // Optional: You can add a method to check the current stock level for a particular item
    public function getStock($menuId)
    {
        $query = 'SELECT Stock FROM menu WHERE MenuId = :menuId';
        $this->db->query($query);
        $this->db->bind(':menuId', $menuId);
        return $this->db->single(); // Returns single row (Stock value)
    }
}
