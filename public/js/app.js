// ============================================================
// 1. STATE MANAGEMENT
// ============================================================
let cart = JSON.parse(localStorage.getItem('ps_cart') || '[]');

// ============================================================
// 2. RESPONSIVE UI (HAMBURGER & PROFILE DROPDOWN)
// ============================================================
function initResponsiveUI() {
    const hamburgerBtn = document.getElementById('hamburgerMenuBtn');
    const mobileNavMenu = document.getElementById('mobileNavMenu');

    if (hamburgerBtn && mobileNavMenu) {
        hamburgerBtn.replaceWith(hamburgerBtn.cloneNode(true));
        const newHamburgerBtn = document.getElementById('hamburgerMenuBtn');

        newHamburgerBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            newHamburgerBtn.classList.toggle('open'); 
            mobileNavMenu.classList.toggle('show');   
        });
    }

    const wrap = document.getElementById('profileWrap');
    if (wrap) {
        const btn = wrap.querySelector('.profile-btn');
        if (btn) {
            btn.replaceWith(btn.cloneNode(true));
            const newBtn = wrap.querySelector('.profile-btn');

            newBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                wrap.classList.toggle('open');
            });
        }
    }

    
    document.addEventListener('click', (e) => {
        const currentHamburger = document.getElementById('hamburgerMenuBtn');
        const currentMobileMenu = document.getElementById('mobileNavMenu');

        if (currentMobileMenu && currentMobileMenu.classList.contains('show')) {
            if (!currentMobileMenu.contains(e.target) && !currentHamburger.contains(e.target)) {
                currentHamburger.classList.remove('open');
                currentMobileMenu.classList.remove('show');
            }
        }

        if (wrap && wrap.classList.contains('open') && !wrap.contains(e.target)) {
            wrap.classList.remove('open');
        }
    });
}

// ============================================================
// 3. CART LOGIC & SIDEBAR RENDERING
// ============================================================
function addToCart(id, name, price, image) {
    const existing = cart.find(item => item.id === id);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ id, name, price, image, qty: 1 });
    }
    
    saveCart();
}

function saveCart() {
    localStorage.setItem('ps_cart', JSON.stringify(cart));
    updateCartUI();
    renderCartItems();
}

function renderCartItems() {
    const container = document.getElementById('cartItemsList');
    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = `<div class="cart-empty-msg">Cart is empty</div>`;
        return;
    }

    container.innerHTML = cart.map(item => `
        <div class="cart-item">
            <img src="/storage/products/${item.image}" onerror="this.src='https://via.placeholder.com/50'" class="cart-item-img">
            <div class="cart-item-details">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">${(item.price * item.qty).toFixed(2)} BDT</div>
                <div class="cart-qty-controls">
                    <button class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                    <span class="qty-val">${item.qty}</span>
                    <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                </div>
            </div>
            <button onclick="removeFromCart(${item.id})" class="cart-remove-btn">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `).join('');
}

function updateCartUI() {
    const count = cart.reduce((n, item) => n + item.qty, 0);
    document.querySelectorAll('.cart-count').forEach(el => {
        el.textContent = count;
        if (count > 0) {
            el.classList.add('show');
        } else {
            el.classList.remove('show');
        }
    });
    
    const totalEl = document.getElementById('cartTotal');
    if (totalEl) {
        totalEl.textContent = cart.reduce((sum, item) => sum + item.price * item.qty, 0).toFixed(2) + ' BDT';
    }
}

function openSidebar() {
    document.getElementById('cartSidebar')?.classList.add('show');
    document.getElementById('cartOverlay')?.classList.add('show');
    renderCartItems();
}


window.updateQty = function(id, delta) {
    const item = cart.find(i => i.id === id);
    if (item) {
        item.qty += delta;
        if (item.qty <= 0) cart = cart.filter(i => i.id !== id);
        saveCart();
    }
};

window.removeFromCart = function(id) {
    cart = cart.filter(i => i.id !== id);
    saveCart();
};

// ============================================================
// 4. ORDER PLACEMENT (CHECKOUT)
// ============================================================
async function placeOrder() {
    const paymentMethodElement = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethodElement) {
        Swal.fire('Error', 'Please select a payment method!', 'error');
        return;
    }
    const paymentMethod = paymentMethodElement.value;
    const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

    if (cart.length === 0) {
        Swal.fire('Empty Cart', 'Your cart is empty!', 'info');
        return;
    }

    const btn = document.querySelector('.btn-checkout') || document.querySelector('button[onclick="placeOrder()"]');
    if(btn) {
        btn.disabled = true;
        btn.innerText = 'Processing...';
    }

    const targetUrl = (paymentMethod === 'bkash') ? '/bkash/create' : '/place-order';

    try {
        const response = await fetch(targetUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                cart_data: JSON.stringify(cart),
                total_amount: totalAmount,
                payment_method: paymentMethod
            })
        });

        const result = await response.json();

        if (result.success) {  
            if (paymentMethod === 'bkash' && result.payment_url) {
                window.location.href = result.payment_url;
            } else {
                localStorage.removeItem('ps_cart');
                cart = [];
                updateCartUI();
                window.location.href = '/orders';
            }
        }
    } catch (error) {
        if(btn) { btn.disabled = false; btn.innerText = 'Confirm Order'; }
        console.error('Error placing order:', error);
    }
}

function initCheckoutPage() {
    const summaryContainer = document.getElementById('checkoutSummaryItems');
    if (!summaryContainer) return; 
    
    if (cart.length === 0) {
        window.location.href = '/'; 
        return;
    }

    summaryContainer.innerHTML = cart.map(item => `
        <div class="checkout-summary-row">
            <div class="checkout-item-meta">
                <img src="/storage/products/${item.image}" class="checkout-img-thumb">
                <div>
                    <h5>${item.name}</h5>
                    <p>${item.qty} x ${item.price} BDT</p>
                </div>
            </div>
            <span>${(item.price * item.qty).toFixed(0)} BDT</span>
        </div>
    `).join('');

    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const deliveryFee = 60; // কন্টেন্ট অনুযায়ী চেঞ্জেবল

    document.getElementById('summarySubtotal').textContent = subtotal.toFixed(2) + ' BDT';
    document.getElementById('summaryTotal').textContent = (subtotal + deliveryFee).toFixed(2) + ' BDT';
}

// ============================================================
// 5. LIVE SEARCH SEARCH ENGINE
// ============================================================
function initLiveSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    searchInput.addEventListener('keyup', function() {
        let value = this.value.toLowerCase().trim();
        let visibleCount = 0;
        const cards = document.querySelectorAll('.product-card');
        const grid = document.getElementById('productsGrid');

        cards.forEach(card => {
            let name = card.querySelector('.product-name').textContent.toLowerCase();
            let category = card.querySelector('.product-category').textContent.toLowerCase();
            
            if (name.includes(value) || category.includes(value)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const existingEmpty = document.getElementById('searchEmptyState');
        if (visibleCount === 0 && cards.length > 0) {
            if (!existingEmpty) {
                const noResultDiv = document.createElement('div');
                noResultDiv.id = 'searchEmptyState';
                noResultDiv.className = 'search-empty-state';
                noResultDiv.innerHTML = `<h3>No products match your search "${searchInput.value}"</h3>`;
                grid.appendChild(noResultDiv);
            }
        } else if (existingEmpty) {
            existingEmpty.remove();
        }
    });
}

// ============================================================
// 6. GLOBAL INITIALIZATION (DOMContentLoaded)
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
 
    document.documentElement.setAttribute('data-theme', 'light');
    const themeBtn = document.getElementById('themeToggle');
    if (themeBtn) themeBtn.style.display = 'none';


    document.getElementById('cartBtn')?.addEventListener('click', (e) => { 
        e.preventDefault(); 
        openSidebar(); 
    });
    
    document.getElementById('closeSidebarBtn')?.addEventListener('click', function() {
        document.getElementById('cartSidebar')?.classList.remove('show');
        document.getElementById('cartOverlay')?.classList.remove('show');
    });
    
    document.getElementById('cartOverlay')?.addEventListener('click', function() {
        document.getElementById('cartSidebar')?.classList.remove('show');
        document.getElementById('cartOverlay')?.classList.remove('show');
    });


    if (window.location.pathname.includes('/bkash/success')) {
        localStorage.removeItem('ps_cart');
        cart = [];
    }


    initResponsiveUI();
    updateCartUI();
    initLiveSearch();
    if (document.getElementById('checkoutSummaryItems')) {
        initCheckoutPage();
    }
    

    const filterToggle = document.getElementById('filterToggle');
    const filterDrawer = document.getElementById('filterDrawer');
    if (filterToggle && filterDrawer) {
        filterToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            filterDrawer.classList.toggle('open');
        });
    }
});