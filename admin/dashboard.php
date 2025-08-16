<?php
/**
 * Admin Dashboard - Main management interface
 */

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.html');
    exit();
}

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
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="furniture.php">Manage Furniture</a></li>
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
            </div>
        </div>
        
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="card">
                <h3>Total Categories</h3>
                <div class="stat-number"><?php echo count($categories); ?></div>
            </div>
            <div class="card">
                <h3>Total Items</h3>
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
        
        <div class="quick-actions">
            <div class="card">
                <h2>Quick Actions</h2>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="furniture.php?action=add" class="btn btn-primary">Add New Furniture</a>
                    <a href="categories.php?action=add" class="btn btn-success">Add New Category</a>
                    <a href="furniture.php" class="btn btn-primary">View All Furniture</a>
                </div>
            </div>
        </div>
        
        <?php if (!empty($spotlight_items)): ?>
        <div class="spotlight-section">
            <div class="card">
                <h2>Current Spotlight Items</h2>
                <div class="furniture-grid">
                    <?php foreach (array_slice($spotlight_items, 0, 3) as $item): ?>
                    <div class="furniture-item">
                        <img src="../<?php echo htmlspecialchars($item['main_image']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="furniture-item-content">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <div class="price">GHS <?php echo number_format($item['price'], 2); ?></div>
                            <div class="category"><?php echo htmlspecialchars($item['category_name']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="recent-section">
            <div class="card">
                <h2>Recent Furniture Items</h2>
                <div class="furniture-grid">
                    <?php foreach (array_slice($recent_items, 0, 6) as $item): ?>
                    <div class="furniture-item">
                        <img src="../<?php echo htmlspecialchars($item['main_image']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             onerror="this.src='../images/placeholder.jpg'">
                        <div class="furniture-item-content">
                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                            <div class="price">GHS <?php echo number_format($item['price'], 2); ?></div>
                            <div class="category"><?php echo htmlspecialchars($item['category_name'] ?? 'No Category'); ?></div>
                            <div class="actions">
                                <a href="furniture.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-primary">Edit</a>
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

.stats-grid h3 {
    color: #333;
    margin-bottom: 10px;
    font-size: 16px;
}
</style>