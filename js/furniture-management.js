/**
 * Furniture Management JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    const furnitureForm = document.getElementById('furnitureForm');
    
    if (furnitureForm) {
        furnitureForm.addEventListener('submit', handleFurnitureSubmit);
    }
});

async function handleFurnitureSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const itemId = document.getElementById('item_id').value;
    
    // Collect form data
    const name = document.getElementById('name').value.trim();
    const categoryId = document.getElementById('category_id').value;
    const description = document.getElementById('description').value.trim();
    const price = document.getElementById('price').value;
    const isFeatured = document.getElementById('is_featured').checked;
    const isSpotlight = document.getElementById('is_spotlight').checked;
    const imageFile = document.getElementById('main_image').files[0];
    
    if (!name || !categoryId) {
        showNotification('Name and category are required', 'error');
        return;
    }
    
    // Show loading
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    try {
        let imagePath = '';
        
        // Handle image upload if provided
        if (imageFile) {
            imagePath = await uploadImage(imageFile);
        }
        
        // Prepare data for API
        const furnitureData = {
            name: name,
            description: description,
            category_id: parseInt(categoryId),
            price: parseFloat(price) || 0,
            is_featured: isFeatured,
            is_spotlight: isSpotlight,
            main_image: imagePath || document.getElementById('current_image')?.value || ''
        };
        
        // API call
        const url = itemId ? 
            `../backend/api/furniture.php?id=${itemId}` : 
            '../backend/api/furniture.php';
        
        const method = itemId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(furnitureData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => {
                window.location.href = 'furniture.php';
            }, 1500);
        } else {
            showNotification(result.message || 'Operation failed', 'error');
        }
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred while saving', 'error');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
}

// Upload image function
async function uploadImage(file) {
    const formData = new FormData();
    formData.append('image', file);
    
    try {
        const response = await fetch('../backend/api/upload.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            return result.path;
        } else {
            throw new Error(result.message || 'Upload failed');
        }
    } catch (error) {
        console.error('Upload error:', error);
        // Fallback: use a placeholder or existing image path
        return 'images/placeholder.jpg';
    }
}

// Filter furniture by category
function filterByCategory(categoryId) {
    const items = document.querySelectorAll('.furniture-item');
    
    items.forEach(item => {
        const itemCategoryId = item.dataset.categoryId;
        
        if (!categoryId || itemCategoryId === categoryId) {
            item.style.display = 'block';
            item.style.animation = 'fadeInUp 0.4s ease';
        } else {
            item.style.display = 'none';
        }
    });
}

// Search furniture items
function searchFurniture(query) {
    const items = document.querySelectorAll('.furniture-item');
    const lowercaseQuery = query.toLowerCase();
    
    items.forEach(item => {
        const name = item.querySelector('h3').textContent.toLowerCase();
        const category = item.querySelector('.category').textContent.toLowerCase();
        
        if (name.includes(lowercaseQuery) || category.includes(lowercaseQuery)) {
            item.style.display = 'block';
            item.style.animation = 'fadeInUp 0.4s ease';
        } else {
            item.style.display = 'none';
        }
    });
}

// Show notification function (from admin-dashboard.js)
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: bold;
        z-index: 1000;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    
    switch(type) {
        case 'success':
            notification.style.background = '#48bb78';
            break;
        case 'error':
            notification.style.background = '#e53e3e';
            break;
        default:
            notification.style.background = '#667eea';
    }
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}