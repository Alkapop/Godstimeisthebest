/**
 * Category Management JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    const categoryForm = document.getElementById('categoryForm');
    
    if (categoryForm) {
        categoryForm.addEventListener('submit', handleCategorySubmit);
    }
});

async function handleCategorySubmit(e) {
    e.preventDefault();
    
    const categoryId = document.getElementById('category_id').value;
    const name = document.getElementById('name').value.trim();
    const description = document.getElementById('description').value.trim();
    const imageUrl = document.getElementById('image_url').value.trim();
    
    if (!name) {
        showNotification('Category name is required', 'error');
        return;
    }
    
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    try {
        const categoryData = {
            name: name,
            description: description,
            image_url: imageUrl
        };
        
        const url = categoryId ? 
            `../backend/api/categories.php?id=${categoryId}` : 
            '../backend/api/categories.php';
        
        const method = categoryId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(categoryData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => {
                window.location.href = 'categories.php';
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

// Delete category with confirmation
function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category? This will also affect all furniture items in this category.')) {
        fetch(`../backend/api/categories.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Category deleted successfully', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification('Failed to delete category', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }
}

// Show notification function
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