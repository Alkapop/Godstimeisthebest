-- Database Schema for Master Poplampo's Furniture Shop
-- Creates tables for furniture management system

-- Create database
CREATE DATABASE IF NOT EXISTS furniture_shop;
USE furniture_shop;

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Furniture items table
CREATE TABLE furniture_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    category_id INT,
    price DECIMAL(10, 2),
    is_featured BOOLEAN DEFAULT FALSE,
    is_spotlight BOOLEAN DEFAULT FALSE,
    status ENUM('available', 'sold', 'pending', 'disabled') DEFAULT 'available',
    main_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Furniture images table (for multiple images per item)
CREATE TABLE furniture_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    furniture_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    alt_text VARCHAR(200),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (furniture_id) REFERENCES furniture_items(id) ON DELETE CASCADE
);

-- Admin users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager') DEFAULT 'manager',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default categories based on existing gallery
INSERT INTO categories (name, description, image_url) VALUES
('Beds', 'Comfortable and stylish bed designs', 'Gallery_links/Bed.jpg'),
('Furniture', 'Various furniture pieces for your home', 'Gallery_links/Furniture.jpg'),
('Kitchen Cabinets', 'Custom kitchen cabinet solutions', 'Gallery_links/Kitchen.jpg'),
('Shelves', 'Storage and display shelving units', 'Gallery_links/Shelfs.jpg'),
('TV Stands', 'Entertainment center furniture', 'Gallery_links/TV_stands.jpg'),
('Wardrobes', 'Closet and wardrobe solutions', 'Gallery_links/Wadropes.jpg');

-- Insert default admin user (password: admin123 - should be changed)
INSERT INTO admin_users (username, email, password_hash, role) VALUES
('admin', 'admin@poplampo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Sample furniture items
INSERT INTO furniture_items (name, description, category_id, price, is_featured, main_image) VALUES
('Luxury King Bed', 'Handcrafted wooden king size bed with elegant finish', 1, 1200.00, TRUE, 'Gallery/images/Bed/Bed1.jpg'),
('Modern Office Desk', 'Contemporary desk perfect for home office', 2, 450.00, FALSE, 'Gallery/images/Funitures/Furn1.jpg'),
('Custom Kitchen Cabinet Set', 'Complete kitchen cabinet solution with modern design', 3, 2500.00, TRUE, 'Gallery/images/Cabinets/Cab1.jpg'),
('Floating Wall Shelves', 'Space-saving wall mounted shelving system', 4, 180.00, FALSE, 'Gallery/images/Bed/Bed2.jpg'),
('Entertainment Center', 'Large TV stand with storage compartments', 5, 650.00, FALSE, 'Gallery/images/Funitures/Furn2.jpg'),
('Walk-in Wardrobe', 'Custom wardrobe with multiple compartments', 6, 1800.00, TRUE, 'Gallery/images/Wadropes/Wad1.jpg');