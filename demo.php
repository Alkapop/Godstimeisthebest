<?php
/**
 * Demo Mode - Provides sample data when database is not available
 * This allows testing the enhanced features without database setup
 */

class DemoData {
    public static function getFeaturedFurniture() {
        return [
            [
                'id' => 1,
                'name' => 'Luxury King Bed',
                'description' => 'Handcrafted wooden king size bed with elegant finish. Perfect for your master bedroom with premium materials and exceptional craftsmanship.',
                'price' => 1200.00,
                'main_image' => 'images/12.jpg',
                'category_name' => 'Beds',
                'is_featured' => true,
                'is_spotlight' => true
            ],
            [
                'id' => 2,
                'name' => 'Custom Kitchen Cabinet Set',
                'description' => 'Complete kitchen cabinet solution with modern design. Transform your kitchen with our custom-built cabinets tailored to your space.',
                'price' => 2500.00,
                'main_image' => 'images/39.jpg',
                'category_name' => 'Kitchen Cabinets',
                'is_featured' => true,
                'is_spotlight' => true
            ],
            [
                'id' => 3,
                'name' => 'Walk-in Wardrobe',
                'description' => 'Custom wardrobe with multiple compartments. Maximize your storage with our beautifully designed wardrobe solutions.',
                'price' => 1800.00,
                'main_image' => 'images/72.jpg',
                'category_name' => 'Wardrobes',
                'is_featured' => true,
                'is_spotlight' => true
            ]
        ];
    }
    
    public static function getCategories() {
        return [
            ['id' => 1, 'name' => 'Beds', 'description' => 'Comfortable and stylish bed designs', 'image_url' => 'Gallery_links/Bed.jpg'],
            ['id' => 2, 'name' => 'Furniture', 'description' => 'Various furniture pieces for your home', 'image_url' => 'Gallery_links/Furniture.jpg'],
            ['id' => 3, 'name' => 'Kitchen Cabinets', 'description' => 'Custom kitchen cabinet solutions', 'image_url' => 'Gallery_links/Kitchen.jpg'],
            ['id' => 4, 'name' => 'Shelves', 'description' => 'Storage and display shelving units', 'image_url' => 'Gallery_links/Shelfs.jpg'],
            ['id' => 5, 'name' => 'TV Stands', 'description' => 'Entertainment center furniture', 'image_url' => 'Gallery_links/TV_stands.jpg'],
            ['id' => 6, 'name' => 'Wardrobes', 'description' => 'Closet and wardrobe solutions', 'image_url' => 'Gallery_links/Wadropes.jpg']
        ];
    }
}

// Demo API endpoint that works without database
if (isset($_GET['demo']) && $_GET['demo'] === 'true') {
    header('Content-Type: application/json');
    
    if (isset($_GET['type'])) {
        switch ($_GET['type']) {
            case 'featured':
                echo json_encode(['success' => true, 'data' => DemoData::getFeaturedFurniture()]);
                break;
            case 'categories':
                echo json_encode(['success' => true, 'data' => DemoData::getCategories()]);
                break;
            default:
                echo json_encode(['success' => true, 'data' => DemoData::getFeaturedFurniture()]);
        }
    } else {
        echo json_encode(['success' => true, 'data' => DemoData::getFeaturedFurniture()]);
    }
    exit();
}
?>