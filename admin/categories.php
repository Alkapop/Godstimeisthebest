<?php
/**
 * Admin Category Management Page
 */

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.html');
    exit();
}

require_once '../backend/models/Category.php';

$category = new Category();

try {
    $categories = $category->getAll();
} catch (Exception $e) {
    $categories = [];
}

$action = $_GET['action'] ?? 'list';
$edit_item = null;

if ($action === 'edit' && isset($_GET['id'])) {
    try {
        $edit_item = $category->getById($_GET['id']);
    } catch (Exception $e) {
        $edit_item = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-dashboard">
    <header class="admin-header">
        <nav class="admin-nav">
            <h1>Category Management</h1>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="furniture.php">Manage Furniture</a></li>
                <li><a href="categories.php" class="active">Categories</a></li>
                <li><a href="../index.html" target="_blank">View Site</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <main class="admin-content">
        <?php if ($action === 'add' || $action === 'edit'): ?>
        <!-- Add/Edit Category Form -->
        <div class="card">
            <h2><?php echo $action === 'edit' ? 'Edit' : 'Add New'; ?> Category</h2>
            
            <form id="categoryForm">
                <input type="hidden" id="category_id" value="<?php echo $edit_item['id'] ?? ''; ?>">
                
                <div class="form-group">
                    <label for="name">Category Name *</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo htmlspecialchars($edit_item['name'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" 
                              placeholder="Describe this furniture category..."><?php echo htmlspecialchars($edit_item['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="image_url">Category Image URL</label>
                    <input type="text" id="image_url" name="image_url" 
                           placeholder="e.g., Gallery_links/Bed.jpg"
                           value="<?php echo htmlspecialchars($edit_item['image_url'] ?? ''); ?>">
                </div>
                
                <div style="margin-top: 30px; display: flex; gap: 15px;">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $action === 'edit' ? 'Update' : 'Add'; ?> Category
                    </button>
                    <a href="categories.php" class="btn" style="background: #666; color: white;">Cancel</a>
                </div>
            </form>
        </div>
        
        <?php else: ?>
        <!-- Categories List -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>All Categories</h2>
                <a href="categories.php?action=add" class="btn btn-primary">Add New Category</a>
            </div>
            
            <?php if (empty($categories)): ?>
            <p>No categories found. <a href="categories.php?action=add">Add your first category</a>.</p>
            <?php else: ?>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <?php foreach ($categories as $cat): ?>
                <div class="furniture-item">
                    <img src="../<?php echo htmlspecialchars($cat['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($cat['name']); ?>" 
                         onerror="this.src='../images/placeholder.jpg'"
                         style="height: 150px;">
                    <div class="furniture-item-content">
                        <h3><?php echo htmlspecialchars($cat['name']); ?></h3>
                        <p><?php echo htmlspecialchars($cat['description'] ?? 'No description'); ?></p>
                        
                        <div class="actions" style="margin-top: 15px;">
                            <a href="categories.php?action=edit&id=<?php echo $cat['id']; ?>" class="btn btn-primary">Edit</a>
                            <button onclick="deleteCategory(<?php echo $cat['id']; ?>)" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </main>
    
    <script src="../js/admin-dashboard.js"></script>
    <script src="../js/category-management.js"></script>
</body>
</html>