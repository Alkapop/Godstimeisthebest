<?php
/**
 * Category Model - Database operations for furniture categories
 */

require_once '../config/database.php';

class Category {
    private $conn;
    private $table_name = "categories";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get all categories
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single category
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new category
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                 (name, description, image_url)
                 VALUES (:name, :description, :image_url)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':image_url', $data['image_url']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Update category
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                 SET name = :name, description = :description, image_url = :image_url,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':image_url', $data['image_url']);
        
        return $stmt->execute();
    }

    // Delete category
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>