<?php
/**
 * Mock Database for Testing - Furniture Shop
 * This provides a JSON-based storage system for testing when database is not available
 */

class MockDatabase {
    private $dataDir;
    private $furnitureFile;
    private $categoriesFile;
    
    public function __construct() {
        $this->dataDir = __DIR__ . '/mock_data/';
        $this->furnitureFile = $this->dataDir . 'furniture.json';
        $this->categoriesFile = $this->dataDir . 'categories.json';
        
        // Create directory if not exists
        if (!is_dir($this->dataDir)) {
            mkdir($this->dataDir, 0755, true);
        }
        
        // Initialize with sample data if files don't exist
        $this->initializeData();
    }
    
    private function initializeData() {
        // Initialize categories
        if (!file_exists($this->categoriesFile)) {
            $categories = [
                ['id' => 1, 'name' => 'Beds', 'description' => 'Custom beds and bedroom furniture', 'image_url' => 'images/beds.jpg'],
                ['id' => 2, 'name' => 'Kitchen Cabinets', 'description' => 'Custom kitchen cabinets and storage', 'image_url' => 'images/kitchen.jpg'],
                ['id' => 3, 'name' => 'Wardrobes', 'description' => 'Custom wardrobes and closets', 'image_url' => 'images/wardrobe.jpg'],
                ['id' => 4, 'name' => 'TV Stands', 'description' => 'Entertainment centers and TV stands', 'image_url' => 'images/tv-stand.jpg'],
                ['id' => 5, 'name' => 'Shelves', 'description' => 'Custom shelving solutions', 'image_url' => 'images/shelves.jpg'],
                ['id' => 6, 'name' => 'Furniture', 'description' => 'General furniture pieces', 'image_url' => 'images/furniture.jpg'],
            ];
            file_put_contents($this->categoriesFile, json_encode($categories, JSON_PRETTY_PRINT));
        }
        
        // Initialize furniture
        if (!file_exists($this->furnitureFile)) {
            $furniture = [
                [
                    'id' => 1,
                    'name' => 'Luxury King Bed',
                    'description' => 'Handcrafted wooden king size bed with elegant finish',
                    'category_id' => 1,
                    'category_name' => 'Beds',
                    'price' => 1200.00,
                    'is_featured' => true,
                    'is_spotlight' => true,
                    'main_image' => 'images/12.jpg',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 2,
                    'name' => 'Custom Kitchen Cabinet Set',
                    'description' => 'Complete kitchen cabinet solution with modern design',
                    'category_id' => 2,
                    'category_name' => 'Kitchen Cabinets',
                    'price' => 2500.00,
                    'is_featured' => true,
                    'is_spotlight' => true,
                    'main_image' => 'images/39.jpg',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'id' => 3,
                    'name' => 'Walk-in Wardrobe',
                    'description' => 'Custom wardrobe with multiple compartments',
                    'category_id' => 3,
                    'category_name' => 'Wardrobes',
                    'price' => 1800.00,
                    'is_featured' => true,
                    'is_spotlight' => true,
                    'main_image' => 'images/72.jpg',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ];
            file_put_contents($this->furnitureFile, json_encode($furniture, JSON_PRETTY_PRINT));
        }
    }
    
    // Get all categories
    public function getCategories() {
        if (!file_exists($this->categoriesFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->categoriesFile), true) ?: [];
    }
    
    // Get all furniture
    public function getFurniture($category_id = null, $featured_only = false, $spotlight_only = false) {
        if (!file_exists($this->furnitureFile)) {
            return [];
        }
        
        $furniture = json_decode(file_get_contents($this->furnitureFile), true) ?: [];
        
        // Apply filters
        if ($category_id) {
            $furniture = array_filter($furniture, function($item) use ($category_id) {
                return $item['category_id'] == $category_id;
            });
        }
        
        if ($featured_only) {
            $furniture = array_filter($furniture, function($item) {
                return $item['is_featured'];
            });
        }
        
        if ($spotlight_only) {
            $furniture = array_filter($furniture, function($item) {
                return $item['is_spotlight'];
            });
        }
        
        return array_values($furniture);
    }
    
    // Get single furniture item
    public function getFurnitureById($id) {
        $furniture = $this->getFurniture();
        foreach ($furniture as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        return null;
    }
    
    // Create furniture item
    public function createFurniture($data) {
        $furniture = $this->getFurniture();
        $categories = $this->getCategories();
        
        // Find category name
        $categoryName = '';
        foreach ($categories as $cat) {
            if ($cat['id'] == $data['category_id']) {
                $categoryName = $cat['name'];
                break;
            }
        }
        
        // Generate new ID
        $maxId = 0;
        foreach ($furniture as $item) {
            if ($item['id'] > $maxId) {
                $maxId = $item['id'];
            }
        }
        
        $newItem = [
            'id' => $maxId + 1,
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'category_id' => (int)$data['category_id'],
            'category_name' => $categoryName,
            'price' => (float)($data['price'] ?? 0),
            'is_featured' => (bool)($data['is_featured'] ?? false),
            'is_spotlight' => (bool)($data['is_spotlight'] ?? false),
            'main_image' => $data['main_image'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $furniture[] = $newItem;
        file_put_contents($this->furnitureFile, json_encode($furniture, JSON_PRETTY_PRINT));
        
        return $newItem['id'];
    }
    
    // Update furniture item
    public function updateFurniture($id, $data) {
        $furniture = $this->getFurniture();
        $categories = $this->getCategories();
        
        // Find category name
        $categoryName = '';
        foreach ($categories as $cat) {
            if ($cat['id'] == $data['category_id']) {
                $categoryName = $cat['name'];
                break;
            }
        }
        
        $found = false;
        foreach ($furniture as &$item) {
            if ($item['id'] == $id) {
                $item['name'] = $data['name'];
                $item['description'] = $data['description'] ?? '';
                $item['category_id'] = (int)$data['category_id'];
                $item['category_name'] = $categoryName;
                $item['price'] = (float)($data['price'] ?? 0);
                $item['is_featured'] = (bool)($data['is_featured'] ?? false);
                $item['is_spotlight'] = (bool)($data['is_spotlight'] ?? false);
                if (!empty($data['main_image'])) {
                    $item['main_image'] = $data['main_image'];
                }
                $item['updated_at'] = date('Y-m-d H:i:s');
                $found = true;
                break;
            }
        }
        
        if ($found) {
            file_put_contents($this->furnitureFile, json_encode($furniture, JSON_PRETTY_PRINT));
            return true;
        }
        
        return false;
    }
    
    // Delete furniture item
    public function deleteFurniture($id) {
        $furniture = $this->getFurniture();
        $newFurniture = array_filter($furniture, function($item) use ($id) {
            return $item['id'] != $id;
        });
        
        if (count($newFurniture) < count($furniture)) {
            file_put_contents($this->furnitureFile, json_encode(array_values($newFurniture), JSON_PRETTY_PRINT));
            return true;
        }
        
        return false;
    }
}
?>