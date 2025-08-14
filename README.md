# Master Poplampo's Furniture Shop - Enhanced Web Application

## Overview
This project has been transformed from a static HTML website into a dynamic, full-featured furniture shop web application with backend management capabilities.

## New Features Added

### 🎨 Frontend Enhancements
- **Dynamic Animations**: Smooth scroll effects, fade-in animations, and hover transitions
- **Spotlight Carousel**: Featured furniture items with auto-rotating showcase
- **Enhanced Gallery**: Lightbox functionality for image viewing
- **Mobile Optimizations**: Improved responsive design and mobile menu
- **Interactive Elements**: Smooth scrolling navigation and enhanced user experience

### 🖥️ Backend System
- **PHP Backend**: Complete RESTful API for furniture and category management
- **Database Integration**: MySQL database with proper schema for furniture inventory
- **Admin Authentication**: Secure login system for administrative access
- **CRUD Operations**: Full Create, Read, Update, Delete functionality

### 👨‍💼 Admin Panel Features
- **Dashboard Overview**: Statistics and quick actions for furniture management
- **Furniture Management**: Add, edit, delete furniture items with image uploads
- **Category Management**: Organize furniture into categories
- **Spotlight Control**: Set featured and spotlight items for homepage display
- **Image Upload**: Multimedia management for furniture photos

## Setup Instructions

### Prerequisites
- Web server with PHP 7.4+ support (Apache/Nginx)
- MySQL 5.7+ or MariaDB
- PHP extensions: PDO, PDO_MySQL

### Installation
1. **Database Setup**:
   - Create a MySQL database named `furniture_shop`
   - Update database credentials in `config/database.php`
   - Run `database/init.php` in your browser to initialize the database

2. **File Permissions**:
   ```bash
   chmod 755 uploads/
   chmod 755 uploads/furniture/
   ```

3. **Admin Access**:
   - Default admin login: `admin` / `admin123`
   - Access admin panel at `/admin/index.html`

### Usage
1. **Frontend**: Browse the enhanced furniture shop with dynamic features
2. **Admin Panel**: Log in to manage furniture inventory, categories, and featured items
3. **API Endpoints**: 
   - `backend/api/furniture.php` - Furniture operations
   - `backend/api/categories.php` - Category operations
   - `backend/api/upload.php` - Image upload handling

## File Structure
```
├── admin/                  # Admin panel interface
├── backend/               # PHP backend and API
├── config/                # Database configuration
├── css/                   # Stylesheets (enhanced)
├── database/              # Database schema and initialization
├── Gallery/               # Gallery pages and images
├── js/                    # JavaScript files (new)
├── uploads/               # User uploaded images
└── index.html            # Enhanced main page
```

## Technologies Used
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.4+, MySQL
- **Features**: Responsive design, REST API, File uploads, Session management

---
*Transformed and enhanced by Advanced Development Team*
