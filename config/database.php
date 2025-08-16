<?php
/**
 * Database Configuration for Furniture Shop
 * Master Poplampo's Furniture Shop Backend
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'furniture_shop';
    private $username = 'root';
    private $password = 'root';  // Changed from 'root' to empty string for better compatibility
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            // Try with empty password first (common default)
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // If empty password fails, try with 'root' password
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                    $this->username,
                    'root'
                );
                $this->conn->exec("set names utf8");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $second_exception) {
                // Log error for debugging but don't expose to user
                error_log("Database connection failed: " . $second_exception->getMessage());
                // Don't echo connection errors to avoid exposing sensitive information
                throw new Exception("Database connection unavailable. Please check your database configuration.");
            }
        }
        
        return $this->conn;
    }
}
?>