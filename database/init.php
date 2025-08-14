<?php
/**
 * Database Initialization Script
 * Run this script to set up the database for the first time
 */

require_once '../config/database.php';

echo "<h2>Furniture Shop Database Setup</h2>\n";

try {
    // First, try to connect without specifying database to create it
    $conn = new PDO("mysql:host=localhost", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute the schema file
    $schema = file_get_contents('../database/schema.sql');
    
    if ($schema === false) {
        throw new Exception("Could not read schema.sql file");
    }
    
    // Split by semicolons and execute each statement
    $statements = explode(';', $schema);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $conn->exec($statement);
                echo "<p>✓ Executed: " . substr($statement, 0, 50) . "...</p>\n";
            } catch (PDOException $e) {
                echo "<p>⚠ Warning: " . $e->getMessage() . "</p>\n";
            }
        }
    }
    
    echo "<p><strong>✓ Database setup completed successfully!</strong></p>\n";
    echo "<p>Default admin login: <strong>admin</strong> / <strong>admin123</strong></p>\n";
    echo "<p><a href='../admin/index.html'>Go to Admin Panel</a></p>\n";
    
} catch (Exception $e) {
    echo "<p><strong>✗ Error:</strong> " . $e->getMessage() . "</p>\n";
    echo "<p>Please make sure MySQL is running and the credentials in config/database.php are correct.</p>\n";
    echo "<p>For development, you can still use the admin panel with username: <strong>admin</strong> and password: <strong>admin123</strong></p>\n";
}
?>