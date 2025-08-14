/**
 * Admin Login JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const errorDiv = document.getElementById('error-message');
    
    // Check for login error from session
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('error')) {
        showError('Invalid username or password');
    }
    
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        
        if (!username || !password) {
            showError('Please enter both username and password');
            return;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('.login-btn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Logging in...';
        submitBtn.disabled = true;
        
        // Submit the form
        setTimeout(() => {
            loginForm.submit();
        }, 500);
    });
    
    function showError(message) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        
        // Add shake animation
        errorDiv.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            errorDiv.style.animation = '';
        }, 500);
    }
    
    // Add floating animation to login container
    const loginContainer = document.querySelector('.login-container');
    loginContainer.style.animation = 'fadeInUp 0.6s ease-out';
});

// CSS animations (will be added to admin.css)
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);