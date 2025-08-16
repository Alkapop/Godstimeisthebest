/**
 * Frontend Enhancement JavaScript
 * Adds animations and dynamic features to the furniture shop
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeAnimations();
    initializeSpotlight();
    initializeGalleryEnhancements();
    initializeMobileMenu();
    loadFeaturedItems();
});

// Initialize scroll-based animations
function initializeAnimations() {
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Fade in elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe sections for animation
    document.querySelectorAll('section').forEach(section => {
        section.classList.add('fade-element');
        observer.observe(section);
    });
    
    // Add hover effects to service cards
    document.querySelectorAll('.Services a').forEach(serviceCard => {
        serviceCard.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.05)';
        });
        
        serviceCard.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// Initialize spotlight carousel
function initializeSpotlight() {
    const spotlightContainer = document.getElementById('spotlight-container');
    if (!spotlightContainer) return;
    
    let currentSlide = 0;
    const slides = spotlightContainer.querySelectorAll('.spotlight-item');
    const totalSlides = slides.length;
    
    if (totalSlides === 0) return;
    
    // Auto-rotate spotlight
    setInterval(() => {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % totalSlides;
        slides[currentSlide].classList.add('active');
    }, 5000);
    
    // Add navigation dots
    const dotsContainer = document.createElement('div');
    dotsContainer.className = 'spotlight-dots';
    
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('button');
        dot.className = 'spotlight-dot';
        if (i === 0) dot.classList.add('active');
        
        dot.addEventListener('click', () => {
            slides[currentSlide].classList.remove('active');
            document.querySelectorAll('.spotlight-dot').forEach(d => d.classList.remove('active'));
            currentSlide = i;
            slides[currentSlide].classList.add('active');
            dot.classList.add('active');
        });
        
        dotsContainer.appendChild(dot);
    }
    
    spotlightContainer.appendChild(dotsContainer);
}

// Enhance gallery with lightbox
function initializeGalleryEnhancements() {
    // Add lightbox to existing gallery images
    document.querySelectorAll('.photos img').forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
            openLightbox(this.src, this.alt);
        });
    });
}

// Lightbox functionality
function openLightbox(src, alt) {
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <div class="lightbox-content">
            <span class="lightbox-close">&times;</span>
            <img src="${src}" alt="${alt}">
            <div class="lightbox-caption">${alt}</div>
        </div>
    `;
    
    document.body.appendChild(lightbox);
    document.body.style.overflow = 'hidden';
    
    // Close lightbox events
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox || e.target.classList.contains('lightbox-close')) {
            closeLightbox(lightbox);
        }
    });
    
    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox(lightbox);
        }
    });
    
    // Animate in
    setTimeout(() => {
        lightbox.classList.add('active');
    }, 10);
}

function closeLightbox(lightbox) {
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
    setTimeout(() => {
        if (lightbox.parentNode) {
            document.body.removeChild(lightbox);
        }
    }, 300);
}

// Mobile menu toggle
function initializeMobileMenu() {
    const navigation = document.querySelector('.navigation');
    if (!navigation) return;
    
    // Create mobile menu toggle button
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'mobile-menu-toggle';
    toggleBtn.innerHTML = '☰';
    toggleBtn.style.cssText = `
        display: none;
        background: none;
        border: none;
        font-size: 24px;
        color: #000;
        cursor: pointer;
        padding: 10px;
    `;
    
    navigation.appendChild(toggleBtn);
    
    toggleBtn.addEventListener('click', function() {
        const nav = navigation.querySelector('nav');
        nav.classList.toggle('mobile-open');
    });
    
    // Show/hide toggle button based on screen size
    function checkMobileMenu() {
        if (window.innerWidth <= 768) {
            toggleBtn.style.display = 'block';
        } else {
            toggleBtn.style.display = 'none';
            const nav = navigation.querySelector('nav');
            nav.classList.remove('mobile-open');
        }
    }
    
    window.addEventListener('resize', checkMobileMenu);
    checkMobileMenu();
}

// Load and display featured/spotlight items
async function loadFeaturedItems() {
    try {
        const response = await fetch('backend/api/furniture.php?featured=true');
        const data = await response.json();
        
        if (data.success && data.data.length > 0) {
            createSpotlightSection(data.data);
        }
    } catch (error) {
        console.log('Featured items will be available when backend is configured');
    }
}

// Create dynamic spotlight section
function createSpotlightSection(items) {
    const mainSection = document.querySelector('.Services');
    if (!mainSection) return;
    
    const spotlightSection = document.createElement('section');
    spotlightSection.className = 'spotlight';
    spotlightSection.id = 'spotlight-container';
    
    spotlightSection.innerHTML = `
        <div class="container">
            <h2 class="spotlight-title">Featured Furniture</h2>
            <div class="spotlight-carousel">
                ${items.map((item, index) => `
                    <div class="spotlight-item ${index === 0 ? 'active' : ''}">
                        <img src="${item.main_image}" alt="${item.name}">
                        <div class="spotlight-content">
                            <h3>${item.name}</h3>
                            <p>${item.description}</p>
                            <div class="spotlight-price">GHS ${parseFloat(item.price).toFixed(2)}</div>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
    
    mainSection.parentNode.insertBefore(spotlightSection, mainSection.nextSibling);
    
    // Initialize spotlight after creation
    setTimeout(() => {
        initializeSpotlight();
    }, 100);
}

// Parallax scrolling effect
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll('.main');
    
    parallaxElements.forEach(element => {
        const speed = 0.5;
        element.style.transform = `translateY(${scrolled * speed}px)`;
    });
});

// Add loading animation
function showLoading() {
    const loader = document.createElement('div');
    loader.className = 'loader';
    loader.innerHTML = `
        <div class="loader-spinner"></div>
        <p>Loading...</p>
    `;
    document.body.appendChild(loader);
    return loader;
}

function hideLoading(loader) {
    if (loader && loader.parentNode) {
        loader.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(loader);
        }, 300);
    }
}