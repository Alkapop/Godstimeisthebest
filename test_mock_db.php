<?php
/**
 * Test furniture API with mock database
 */

// Change to the backend directory
chdir(__DIR__ . '/backend');

// Test if the furniture model works
require_once 'models/Furniture.php';
require_once 'models/Category.php';

echo "Testing Furniture Model with Mock Database:\n";

try {
    $furniture = new Furniture();
    $category = new Category();
    
    echo "1. Testing categories...\n";
    $categories = $category->getAll();
    echo "   Found " . count($categories) . " categories\n";
    foreach ($categories as $cat) {
        echo "   - {$cat['name']}\n";
    }
    
    echo "\n2. Testing furniture items...\n";
    $items = $furniture->getAll();
    echo "   Found " . count($items) . " furniture items\n";
    foreach ($items as $item) {
        echo "   - {$item['name']} (ID: {$item['id']}, Category: {$item['category_name']})\n";
    }
    
    echo "\n3. Testing create new item...\n";
    $newData = [
        'name' => 'Test Dining Table',
        'description' => 'Beautiful handcrafted dining table',
        'category_id' => 6,
        'price' => 850.00,
        'is_featured' => false,
        'is_spotlight' => false,
        'main_image' => 'images/test-table.jpg'
    ];
    
    $newId = $furniture->create($newData);
    if ($newId) {
        echo "   Successfully created item with ID: $newId\n";
        
        echo "\n4. Testing get by ID...\n";
        $retrievedItem = $furniture->getById($newId);
        if ($retrievedItem) {
            echo "   Retrieved: {$retrievedItem['name']}\n";
        }
        
        echo "\n5. Testing update...\n";
        $updateData = $newData;
        $updateData['name'] = 'Updated Dining Table';
        $updateData['price'] = 950.00;
        
        $updateResult = $furniture->update($newId, $updateData);
        if ($updateResult) {
            echo "   Successfully updated item\n";
        }
        
        echo "\n6. Testing delete...\n";
        $deleteResult = $furniture->delete($newId);
        if ($deleteResult) {
            echo "   Successfully deleted item\n";
        }
    }
    
    echo "\nMock database test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>