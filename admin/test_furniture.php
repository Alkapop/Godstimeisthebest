<?php
/**
 * Test Admin Furniture Management without Authentication
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
    $all_furniture = $furniture->getAll();
} catch (Exception $e) {
    $categories = [];
    $all_furniture = [];
}

$action = $_GET['action'] ?? 'list';
$edit_item = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $furnitureData = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'category_id' => (int)($_POST['category_id'] ?? 0),
        'price' => (float)($_POST['price'] ?? 0),
        'is_featured' => isset($_POST['is_featured']),
        'is_spotlight' => isset($_POST['is_spotlight']),
        'main_image' => $_POST['main_image'] ?? ''
    ];
    
    try {
        if (!empty($_POST['item_id'])) {
            // Update existing item
            $result = $furniture->update($_POST['item_id'], $furnitureData);
            $message = $result ? "Furniture item updated successfully!" : "Failed to update furniture item.";
        } else {
            // Create new item
            $result = $furniture->create($furnitureData);
            $message = $result ? "Furniture item created successfully!" : "Failed to create furniture item.";
        }
        
        if ($result) {
            header("Location: test_furniture.php?message=" . urlencode($message));
            exit();
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

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
                <li><a href="test_dashboard.php">Dashboard</a></li>
                <li><a href="test_furniture.php" class="active">Manage Furniture</a></li>
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
            
            <?php if (isset($error)): ?>
            <div style="background: #fee; color: #c33; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="test_furniture.php?action=<?php echo $action; ?><?php echo $action === 'edit' ? '&id=' . ($_GET['id'] ?? '') : ''; ?>">
                <?php if ($action === 'edit' && isset($_GET['id'])): ?>
                <input type="hidden" name="item_id" value="<?php echo $_GET['id']; ?>">
                <?php endif; ?>
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
                        <label for="main_image">Main Image Path</label>
                        <input type="text" id="main_image" name="main_image" 
                               placeholder="e.g., images/furniture.jpg"
                               value="<?php echo $edit_item['main_image'] ?? ''; ?>">
                        <?php if ($edit_item && $edit_item['main_image']): ?>
                        <p>Current: <?php echo basename($edit_item['main_image']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="is_featured" name="is_featured" 
                                   <?php echo ($edit_item['is_featured'] ?? false) ? 'checked' : ''; ?>>
                            Featured Item
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="is_spotlight" name="is_spotlight" 
                                   <?php echo ($edit_item['is_spotlight'] ?? false) ? 'checked' : ''; ?>>
                            Spotlight Item
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $action === 'edit' ? 'Update' : 'Add'; ?> Furniture
                    </button>
                    <a href="test_furniture.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
        
        <?php else: ?>
        <!-- Furniture List -->
        <div class="card">
            <?php if (isset($_GET['message'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
            <?php endif; ?>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Furniture Inventory</h2>
                <a href="test_furniture.php?action=add" class="btn btn-primary">Add New Furniture</a>
            </div>
            
            <div class="furniture-grid">
                <?php foreach ($all_furniture as $item): ?>
                <div class="furniture-item" data-category-id="<?php echo $item['category_id']; ?>">
                    <?php if ($item['main_image']): ?>
                    <img src="../<?php echo htmlspecialchars($item['main_image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <?php endif; ?>
                    <div class="furniture-item-content">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <div class="price">GHS <?php echo number_format($item['price'], 2); ?></div>
                        <div class="category"><?php echo htmlspecialchars($item['category_name'] ?? 'No Category'); ?></div>
                        <?php if ($item['is_featured']): ?>
                        <span class="badge featured">Featured</span>
                        <?php endif; ?>
                        <?php if ($item['is_spotlight']): ?>
                        <span class="badge spotlight">Spotlight</span>
                        <?php endif; ?>
                        <div class="actions">
                            <a href="test_furniture.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-primary">Edit</a>
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
        </div>
        
        <?php endif; ?>
    </main>
    
    <script src="../js/admin-dashboard.js"></script>
    <script src="../js/furniture-management.js"></script>
</body>
</html>

<style>
.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
    margin: 5px 5px 5px 0;
}

.badge.featured {
    background: #48bb78;
    color: white;
}

.badge.spotlight {
    background: #ffeb3b;
    color: #333;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}
</style>