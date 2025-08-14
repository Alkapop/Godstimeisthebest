<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Guide - Master Poplampo's Furniture Shop</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        body { background: #f5f6fa; padding: 20px; }
        .setup-container { max-width: 800px; margin: 0 auto; }
        .step { margin-bottom: 30px; }
        .code-block { background: #2d3748; color: #e2e8f0; padding: 15px; border-radius: 8px; font-family: monospace; }
        .status { padding: 10px; border-radius: 5px; margin: 10px 0; }
        .status.success { background: #c6f6d5; color: #276749; }
        .status.warning { background: #fef5e7; color: #b7791f; }
        .status.error { background: #fed7d7; color: #c53030; }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="card">
            <h1>🚀 Furniture Shop Enhancement Setup Guide</h1>
            
            <div class="step">
                <h2>Step 1: Check PHP Installation</h2>
                <p>PHP Status: 
                    <?php if (version_compare(PHP_VERSION, '7.4.0') >= 0): ?>
                        <span class="status success">✅ PHP <?php echo PHP_VERSION; ?> (Ready)</span>
                    <?php else: ?>
                        <span class="status error">❌ PHP 7.4+ required</span>
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="step">
                <h2>Step 2: Database Setup</h2>
                <p>For full functionality, set up MySQL database:</p>
                <div class="code-block">
mysql -u root -p<br>
CREATE DATABASE furniture_shop;<br>
</div>
                <p>Then run: <a href="database/init.php" class="btn btn-primary">Initialize Database</a></p>
                
                <div class="status warning">
                    <strong>Demo Mode Available:</strong> You can test the features without database using demo data.
                </div>
            </div>
            
            <div class="step">
                <h2>Step 3: Test Enhanced Features</h2>
                
                <h3>🎨 Frontend Enhancements</h3>
                <ul>
                    <li><strong>Spotlight Carousel:</strong> Featured furniture with auto-rotation</li>
                    <li><strong>Smooth Animations:</strong> Hover effects and transitions</li>
                    <li><strong>Lightbox Gallery:</strong> Click on images for enlarged view</li>
                    <li><strong>Mobile Optimizations:</strong> Enhanced responsive design</li>
                </ul>
                
                <h3>🔧 Admin Panel Features</h3>
                <ul>
                    <li><strong>Dashboard:</strong> Overview and statistics</li>
                    <li><strong>Furniture Management:</strong> Add, edit, delete items</li>
                    <li><strong>Category Management:</strong> Organize furniture types</li>
                    <li><strong>Image Uploads:</strong> Multimedia management</li>
                    <li><strong>Spotlight Control:</strong> Feature items on homepage</li>
                </ul>
                
                <div style="margin-top: 20px;">
                    <a href="index.html" class="btn btn-primary">🏠 View Enhanced Homepage</a>
                    <a href="admin/index.html" class="btn btn-success">🔑 Admin Panel (admin/admin123)</a>
                </div>
            </div>
            
            <div class="step">
                <h2>Step 4: File Permissions</h2>
                <p>Ensure upload directory is writable:</p>
                <div class="code-block">
chmod 755 uploads/<br>
chmod 755 uploads/furniture/
</div>
            </div>
            
            <div class="step">
                <h2>🎯 What's New</h2>
                <div class="status success">
                    <strong>✅ Transformation Complete!</strong><br>
                    Your static website is now a dynamic, full-featured furniture shop management system.
                </div>
                
                <h3>Key Improvements:</h3>
                <ul>
                    <li>Dynamic spotlight carousel showcasing featured furniture</li>
                    <li>Smooth animations and interactive elements</li>
                    <li>Complete PHP backend with REST API</li>
                    <li>Admin panel for inventory management</li>
                    <li>Image upload and multimedia handling</li>
                    <li>Database-driven content management</li>
                    <li>Enhanced mobile responsiveness</li>
                    <li>Lightbox gallery functionality</li>
                </ul>
            </div>
            
            <div class="step">
                <h2>🔧 Technical Details</h2>
                <p><strong>Frontend:</strong> Enhanced HTML5, CSS3 with animations, JavaScript ES6+</p>
                <p><strong>Backend:</strong> PHP 7.4+, MySQL, RESTful API</p>
                <p><strong>Features:</strong> Admin panel, CRUD operations, file uploads, session management</p>
                <p><strong>Security:</strong> Password hashing, input validation, secure file uploads</p>
            </div>
        </div>
    </div>
</body>
</html>