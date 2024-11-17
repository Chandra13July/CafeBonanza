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
}
