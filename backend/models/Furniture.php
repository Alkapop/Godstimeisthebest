<?php
/**
 * Furniture Model - Database operations for furniture items
 */

require_once '../config/database.php';
require_once __DIR__ . '/../MockDatabase.php';

class Furniture {
    private $conn;
    private $table_name = "furniture_items";
    private $mockDb;
    private $useMockDb = false;

    public function __construct() {
        try {
            $database = new Database();
            $this->conn = $database->getConnection();
            
            // Test the connection
            if (!$this->conn) {
                throw new Exception("Database connection failed");
            }
            
            // Test a simple query to ensure database is working
            $stmt = $this->conn->prepare("SELECT 1");
            $stmt->execute();
            
        } catch (Exception $e) {
            // If database connection fails, use mock database
            $this->useMockDb = true;
            $this->mockDb = new MockDatabase();
            error_log("Using mock database due to connection failure: " . $e->getMessage());
        }
    }

    // Get all furniture items
    public function getAll($category_id = null, $featured_only = false, $spotlight_only = false) {
        if ($this->useMockDb) {
            return $this->mockDb->getFurniture($category_id, $featured_only, $spotlight_only);
        }
        
        $query = "SELECT f.*, c.name as category_name 
                 FROM " . $this->table_name . " f 
                 LEFT JOIN categories c ON f.category_id = c.id 
                 WHERE f.status = 'available'";
        
        if ($category_id) {
            $query .= " AND f.category_id = :category_id";
        }
        if ($featured_only) {
            $query .= " AND f.is_featured = 1";
        }
        if ($spotlight_only) {
            $query .= " AND f.is_spotlight = 1";
        }
        
        $query .= " ORDER BY f.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if ($category_id) {
            $stmt->bindParam(':category_id', $category_id);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single furniture item
    public function getById($id) {
        if ($this->useMockDb) {
            return $this->mockDb->getFurnitureById($id);
        }
        
        $query = "SELECT f.*, c.name as category_name 
                 FROM " . $this->table_name . " f 
                 LEFT JOIN categories c ON f.category_id = c.id 
                 WHERE f.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new furniture item
    public function create($data) {
        if ($this->useMockDb) {
            return $this->mockDb->createFurniture($data);
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                 (name, description, category_id, price, is_featured, is_spotlight, main_image)
                 VALUES (:name, :description, :category_id, :price, :is_featured, :is_spotlight, :main_image)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':is_featured', $data['is_featured']);
        $stmt->bindParam(':is_spotlight', $data['is_spotlight']);
        $stmt->bindParam(':main_image', $data['main_image']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Update furniture item
    public function update($id, $data) {
        if ($this->useMockDb) {
            return $this->mockDb->updateFurniture($id, $data);
        }
        
        $query = "UPDATE " . $this->table_name . " 
                 SET name = :name, description = :description, category_id = :category_id, 
                     price = :price, is_featured = :is_featured, is_spotlight = :is_spotlight,
                     main_image = :main_image, updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':is_featured', $data['is_featured']);
        $stmt->bindParam(':is_spotlight', $data['is_spotlight']);
        $stmt->bindParam(':main_image', $data['main_image']);
        
        return $stmt->execute();
    }

    // Delete furniture item
    public function delete($id) {
        if ($this->useMockDb) {
            return $this->mockDb->deleteFurniture($id);
        }
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>