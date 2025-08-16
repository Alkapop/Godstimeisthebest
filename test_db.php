<?php
/**
 * Database connectivity test
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once 'config/database.php';
    
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "Database connection successful!\n";
        
        // Test if tables exist
        $tables = ['categories', 'furniture', 'admin_users'];
        
        foreach ($tables as $table) {
            $query = "SHOW TABLES LIKE '$table'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                echo "Table '$table' exists\n";
                
                // Get row count
                $countQuery = "SELECT COUNT(*) as count FROM $table";
                $countStmt = $conn->prepare($countQuery);
                $countStmt->execute();
                $count = $countStmt->fetch(PDO::FETCH_ASSOC);
                echo "  - $table has {$count['count']} rows\n";
            } else {
                echo "Table '$table' does NOT exist\n";
            }
        }
    } else {
        echo "Database connection failed\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>