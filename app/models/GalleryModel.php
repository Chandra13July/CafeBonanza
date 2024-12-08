<?php

class GalleryModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findGalleryById($id)
    {
        $this->db->query('SELECT * FROM gallery WHERE GalleryId = :GalleryId');
        $this->db->bind(':GalleryId', $id);
        return $this->db->single();
    }

    public function getAllGallery()
    {
        $this->db->query('SELECT GalleryId, Title, Description, ImageUrl FROM gallery');
        return $this->db->resultSet();
    }

    public function addGallery($data)
    {
        $this->db->query("INSERT INTO gallery (Title, Description, ImageUrl) 
                            VALUES (:title, :description, :imageUrl)");

        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':imageUrl', $data['imageUrl']);

        return $this->db->execute();
    }

    public function editGallery($data)
    {
        $query = "UPDATE gallery SET 
                    Title = :title, 
                    Description = :description,
                    ImageUrl = :imageUrl 
                    WHERE GalleryId = :GalleryId";

        $this->db->query($query);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':imageUrl', $data['imageUrl']);
        $this->db->bind(':GalleryId', $data['GalleryId']);

        return $this->db->execute();
    }

    public function deleteGallery($galleryId)
    {
        $this->db->query("DELETE FROM gallery WHERE GalleryId = :GalleryId");
        $this->db->bind(':GalleryId', $galleryId);

        return $this->db->execute();
    }
}
