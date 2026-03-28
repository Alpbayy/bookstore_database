/* ===== Mobile Menu ===== */
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    if (hamburger) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }

    // Close menu when a link is clicked
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
        });
    });
});

/* ===== Add to Cart Feedback ===== */
document.addEventListener('DOMContentLoaded', function() {
    const addToCartForms = document.querySelectorAll('form[action*="add-to-cart"]');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = form.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-check"></i> Added to Cart!';
            button.style.backgroundColor = '#16a34a';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.backgroundColor = '';
            }, 2000);
        });
    });
});

/* ===== Quantity Counter ===== */
function updateQuantity(change, maxQuantity) {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    let newValue = currentValue + change;
    
    if (newValue >= 1 && newValue <= maxQuantity) {
        quantityInput.value = newValue;
    }
}

/* ===== Smooth Scroll ===== */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            document.querySelector(href).scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

/* ===== Product Filter Animation ===== */
document.addEventListener('DOMContentLoaded', function() {
    const bookCards = document.querySelectorAll('.book-card');
    
    bookCards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
        card.style.opacity = '0';
        card.style.animation = 'fadeInUp 0.6s ease-out forwards';
    });
});

/* ===== Form Validation ===== */
function validateCheckoutForm() {
    const form = document.querySelector('.checkout-form form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (input.value.trim() === '') {
                    isValid = false;
                    input.style.borderColor = '#dc2626';
                } else {
                    input.style.borderColor = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    }
}

validateCheckoutForm();

/* ===== Cart Counter Badge ===== */
function updateCartBadge() {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        const count = parseInt(badge.textContent);
        if (count === 0) {
            badge.style.display = 'none';
        }
    }
}

updateCartBadge();

/* ===== Price Formatter ===== */
function formatPrice(price) {
    return '$' + parseFloat(price).toFixed(2);
}

/* ===== Keyboard Shortcuts ===== */
document.addEventListener('keydown', function(e) {
    // Press 'C' to go to cart
    if (e.key === 'c' && !e.ctrlKey) {
        const cartLink = document.querySelector('.cart-link');
        if (cartLink) {
            cartLink.click();
        }
    }
});

/* ===== Lazy Loading Images ===== */
if ('IntersectionObserver' in window) {
    const images = document.querySelectorAll('img[data-src]');
    const imgObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    images.forEach(img => imgObserver.observe(img));
}

/* ===== Toast Notifications ===== */
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: ${type === 'success' ? '#16a34a' : '#dc2626'};
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/* ===== Add CSS Animations ===== */
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
    
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
