<?php
/**
 * Admin Furniture Management Page
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
    $all_furniture = $furniture->getAll();
} catch (Exception $e) {
    $categories = [];
    $all_furniture = [];
}

$action = $_GET['action'] ?? 'list';
$edit_item = null;

if ($action === 'edit' && isset($_GET['id'])) {
    try {
        $edit_item = $furniture->getById($_GET['id']);
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
    <title>Manage Furniture - Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-dashboard">
    <header class="admin-header">
        <nav class="admin-nav">
            <h1>Furniture Management</h1>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="furniture.php" class="active">Manage Furniture</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="../index.html" target="_blank">View Site</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <main class="admin-content">
        <?php if ($action === 'add' || $action === 'edit'): ?>
        <!-- Add/Edit Furniture Form -->
        <div class="card">
            <h2><?php echo $action === 'edit' ? 'Edit' : 'Add New'; ?> Furniture Item</h2>
            
            <form id="furnitureForm" enctype="multipart/form-data">
                <input type="hidden" id="item_id" value="<?php echo $edit_item['id'] ?? ''; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Furniture Name *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo htmlspecialchars($edit_item['name'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo ($edit_item['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" 
                              placeholder="Describe the furniture item..."><?php echo htmlspecialchars($edit_item['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="price">Price (GHS)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0"
                               value="<?php echo $edit_item['price'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="main_image">Main Image</label>
                        <input type="file" id="main_image" name="main_image" accept="image/*" 
                               onchange="previewImage(this)">
                        <?php if ($edit_item && $edit_item['main_image']): ?>
                        <p>Current: <?php echo basename($edit_item['main_image']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" id="is_featured" name="is_featured" 
                               <?php echo ($edit_item['is_featured'] ?? false) ? 'checked' : ''; ?>>
                        Featured Item
                    </label>
                    
                    <label>
                        <input type="checkbox" id="is_spotlight" name="is_spotlight"
                               <?php echo ($edit_item['is_spotlight'] ?? false) ? 'checked' : ''; ?>>
                        Spotlight Item
                    </label>
                </div>
                
                <div style="margin-top: 20px;">
                    <img id="image-preview" style="display: none; max-width: 200px; margin-top: 10px; border-radius: 5px;">
                </div>
                
                <div style="margin-top: 30px; display: flex; gap: 15px;">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $action === 'edit' ? 'Update' : 'Add'; ?> Furniture
                    </button>
                    <a href="furniture.php" class="btn" style="background: #666; color: white;">Cancel</a>
                </div>
            </form>
        </div>
        
        <?php else: ?>
        <!-- Furniture List -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>All Furniture Items</h2>
                <a href="furniture.php?action=add" class="btn btn-primary">Add New Furniture</a>
            </div>
            
            <?php if (empty($all_furniture)): ?>
            <p>No furniture items found. <a href="furniture.php?action=add">Add your first item</a>.</p>
            <?php else: ?>
            
            <div class="furniture-grid">
                <?php foreach ($all_furniture as $item): ?>
                <div class="furniture-item">
                    <img src="../<?php echo htmlspecialchars($item['main_image']); ?>" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                         onerror="this.src='../images/placeholder.jpg'">
                    <div class="furniture-item-content">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <div class="price">GHS <?php echo number_format($item['price'], 2); ?></div>
                        <div class="category"><?php echo htmlspecialchars($item['category_name'] ?? 'No Category'); ?></div>
                        
                        <div style="margin: 10px 0;">
                            <?php if ($item['is_featured']): ?>
                            <span class="badge" style="background: #48bb78; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px;">Featured</span>
                            <?php endif; ?>
                            
                            <?php if ($item['is_spotlight']): ?>
                            <span class="badge" style="background: #ffeb3b; color: #333; padding: 3px 8px; border-radius: 3px; font-size: 12px;">Spotlight</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="actions">
                            <a href="furniture.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-primary">Edit</a>
                            <button onclick="deleteFurniture(<?php echo $item['id']; ?>)" class="btn btn-danger">Delete</button>
                            <button onclick="toggleSpotlight(<?php echo $item['id']; ?>, <?php echo $item['is_spotlight'] ? 'true' : 'false'; ?>)" 
                                    class="btn" style="background: #ffeb3b; color: #333;">
                                <?php echo $item['is_spotlight'] ? 'Remove Spotlight' : 'Add Spotlight'; ?>
                            </button>
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
    <script src="../js/furniture-management.js"></script>
</body>
</html>