<?php
/**
 * Admin Authentication Handler
 */

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit();
}

require_once '../config/database.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = 'Username and password are required';
    header('Location: index.html');
    exit();
}

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    $query = "SELECT id, username, email, password_hash, role FROM admin_users WHERE username = :username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // For initial testing, accept simple password until database is set up
    $valid_login = false;
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $valid_login = true;
    } elseif ($username === 'admin' && $password === 'admin123') {
        // Temporary fallback for testing
        $valid_login = true;
        $user = [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@poplampo.com',
            'role' => 'admin'
        ];
    }
    
    if ($valid_login) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_role'] = $user['role'];
        
        // Update last login
        if (isset($user['id'])) {
            $update_query = "UPDATE admin_users SET last_login = CURRENT_TIMESTAMP WHERE id = :id";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bindParam(':id', $user['id']);
            $update_stmt->execute();
        }
        
        header('Location: dashboard.php');
    } else {
        $_SESSION['login_error'] = 'Invalid username or password';
        header('Location: index.html');
    }
    
} catch (Exception $e) {
    $_SESSION['login_error'] = 'Login system temporarily unavailable';
    header('Location: index.html');
}

exit();
?>