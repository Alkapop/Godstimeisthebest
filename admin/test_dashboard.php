<?php
/**
 * Test Admin Dashboard without Authentication
 */

// Simulate logged in session for testing
session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_username'] = 'admin';
$_SESSION['admin_role'] = 'admin';

require_once '../backend/models/Furniture.php';
require_once '../backend/models/Category.php';

$furniture = new Furniture();
$category = new Category();

try {
    $categories = $category->getAll();
    $featured_items = $furniture->getAll(null, true, false);
    $spotlight_items = $furniture->getAll(null, false, true);
    $recent_items = $furniture->getAll();
} catch (Exception $e) {
    // Handle database connection issues gracefully
    $categories = [];
    $featured_items = [];
    $spotlight_items = [];
    $recent_items = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Master Poplampo's Furniture Shop</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-dashboard">
    <header class="admin-header">
        <nav class="admin-nav">
            <h1>Admin Dashboard</h1>
            <ul class="nav-links">
                <li><a href="test_dashboard.php" class="active">Dashboard</a></li>
                <li><a href="test_furniture.php">Manage Furniture</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="../index.html" target="_blank">View Site</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <main class="admin-content">
        <div class="welcome-section">
            <div class="card">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
                <p>Manage your furniture inventory, categories, and featured items from this dashboard.</p>
                <p><strong>Note:</strong> This is a test version using mock database for demonstration.</p>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="card">
                <h3>Total Categories</h3>
                <div class="stat-number"><?php echo count($categories); ?></div>
            </div>
            
            <div class="card">
                <h3>Total Furniture Items</h3>
                <div class="stat-number"><?php echo count($recent_items); ?></div>
            </div>
            
            <div class="card">
                <h3>Featured Items</h3>
                <div class="stat-number"><?php echo count($featured_items); ?></div>
            </div>
            
            <div class="card">
                <h3>Spotlight Items</h3>
                <div class="stat-number"><?php echo count($spotlight_items); ?></div>
            </div>
        </div>
        
        <div class="recent-section">
            <div class="card">
                <h2>Recent Furniture Items</h2>
                <div class="furniture-grid">
                    <?php foreach (array_slice($recent_items, 0, 6) as $item): ?>
                    <div class="furniture-item">
                        <?php if ($item['main_image']): ?>
                        <img src="../<?php echo htmlspecialchars($item['main_image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <?php endif; ?>
                        <div class="furniture-item-content">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <div class="price">GHS <?php echo number_format($item['price'], 2); ?></div>
                            <div class="category"><?php echo htmlspecialchars($item['category_name'] ?? 'No Category'); ?></div>
                            <div class="actions">
                                <a href="test_furniture.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-primary">Edit</a>
                                <button onclick="deleteFurniture(<?php echo $item['id']; ?>)" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    
    <script src="../js/admin-dashboard.js"></script>
</body>
</html>

<style>
.stat-number {
    font-size: 36px;
    font-weight: bold;
    color: #667eea;
    margin-top: 10px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stats-grid h3 {
    color: #333;
    margin-bottom: 10px;
    font-size: 16px;
}
</style>