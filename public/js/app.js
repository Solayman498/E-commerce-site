// 1. State Management
let cart = JSON.parse(localStorage.getItem('ps_cart') || '[]');

// 2. Theme Management (Dark Mode)
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('ps_theme', theme);
    const btn = document.getElementById('themeToggle');
    if (btn) btn.textContent = theme === 'dark' ? '☀️' : '🌙';
}

function toggleTheme() {
    const current = localStorage.getItem('ps_theme') || 'light';
    applyTheme(current === 'dark' ? 'light' : 'dark');
}

// 3. Profile Dropdown
function initProfileDropdown() {
    const wrap = document.getElementById('profileWrap');
    if (!wrap) return;
    const btn = wrap.querySelector('.profile-btn');
    btn?.addEventListener('click', e => {
        e.stopPropagation();
        wrap.classList.toggle('open');
    });
    document.addEventListener('click', () => wrap.classList.remove('open'));
}

// 4. Cart Logic
function addToCart(id, name, price, image) {
    const existing = cart.find(i => i.id === id);
    if (existing) existing.qty++;
    else cart.push({ id, name, price, image, qty: 1 });
    
    localStorage.setItem('ps_cart', JSON.stringify(cart));
    updateCartUI();
    // openSidebar();
}

// 5. Cart Sidebar Rendering
function renderCartItems() {
    const container = document.getElementById('cartItemsList');
    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = `<div style="text-align:center; padding:40px; color:#999;">Cart is empty</div>`;
        return;
    }

    container.innerHTML = cart.map(item => `
        <div class="cart-item" style="display:flex; gap:10px; padding:10px; border-bottom:1px solid #eee;">
            <img src="/storage/products/${item.image}" onerror="this.src='https://via.placeholder.com/50'" style="width:50px; height:50px; object-fit:cover; border-radius:5px;">
            <div style="flex:1;">
                <div style="font-weight:bold; font-size:14px;">${item.name}</div>
                <div style="font-size:12px;">${(item.price * item.qty).toFixed(2)} BDT</div>
                <div style="display:flex; align-items:center; gap:8px; margin-top:5px;">
                    <button onclick="updateQty(${item.id}, -1)">-</button>
                    <span>${item.qty}</span>
                    <button onclick="updateQty(${item.id}, 1)">+</button>
                </div>
            </div>
            <button onclick="removeFromCart(${item.id})" style="color:red; background:none; border:none; cursor:pointer;">🗑</button>
        </div>
    `).join('');
}

// 6. Cart UI Update

function updateCartUI() {
    const count = cart.reduce((n, item) => n + item.qty, 0);
    document.querySelectorAll('.cart-count').forEach(el => {
        el.textContent = count;
        el.classList.toggle('show', count > 0);
    });
    const totalEl = document.getElementById('cartTotal');
    if (totalEl) totalEl.textContent = cart.reduce((sum, item) => sum + item.price * item.qty, 0).toFixed(2) + ' BDT';
}

function openSidebar() {
    document.getElementById('cartSidebar')?.classList.add('show');
    document.getElementById('cartOverlay')?.classList.add('show');
    renderCartItems();
}

function closeSidebar() {
    document.getElementById('cartSidebar')?.classList.remove('show');
    document.getElementById('cartOverlay')?.classList.remove('show');
}

function updateQty(id, delta) {
    const item = cart.find(i => i.id == id);
    if (item) {
        item.qty += delta;
        if (item.qty <= 0) cart = cart.filter(i => i.id !== id);
        localStorage.setItem('ps_cart', JSON.stringify(cart));
        updateCartUI();
        renderCartItems();
    }
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id != id);
    localStorage.setItem('ps_cart', JSON.stringify(cart));
    updateCartUI();
    renderCartItems();
}

// 7. Initialization (DOMContentLoaded)
document.addEventListener('DOMContentLoaded', () => {
    // Theme setup
    const savedTheme = localStorage.getItem('ps_theme') || 'light';
    applyTheme(savedTheme);
    document.getElementById('themeToggle')?.addEventListener('click', toggleTheme);

    // Cart events  
    document.getElementById('cartBtn')?.addEventListener('click', (e) => { e.preventDefault(); openSidebar(); });
    document.getElementById('closeSidebarBtn')?.addEventListener('click', closeSidebar);
    document.getElementById('cartOverlay')?.addEventListener('click', closeSidebar);

    // Other UI
    initProfileDropdown();
    updateCartUI();
});

// 8. Order Placement Function
async function placeOrder() {


    const paymentMethodElement = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethodElement) {
        Swal.fire('Error', 'Please select a payment method!', 'error');
        return;
    }
    const paymentMethod = paymentMethodElement.value;

    const cartData = JSON.stringify(cart);    
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
                cart_data: cartData,
                total_amount: totalAmount,
                payment_method: paymentMethod
            })
        });

        const result = await response.json();

        if (result.success) {  

            localStorage.removeItem('ps_cart');
            cart = [];
            if (typeof updateCartUI === "function") updateCartUI();
            

            if (paymentMethod === 'bkash' && result.payment_url) {
                window.location.href = result.payment_url;
            } else {
                window.location.href = '/orders';
            }
        } else {
            if(btn) { btn.disabled = false; btn.innerText = 'Confirm Order'; }
            Swal.fire('Error', result.message || 'Something went wrong!', 'error');
        }
    } catch (error) {
        if(btn) { btn.disabled = false; btn.innerText = 'Confirm Order'; }
        console.error('Error placing order:', error);
    }
}

//9. Checkout Page Initialization
function initCheckoutPage() {
    const summaryContainer = document.getElementById('checkoutSummaryItems');
    if (!summaryContainer) return; 
    if (cart.length === 0) {
        window.location.href = '/'; 
        return;
    }

    //  show cart items in summary
    summaryContainer.innerHTML = cart.map(item => `
        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl">
            <div class="flex items-center gap-3">
                <img src="/storage/products/${item.image}" class="w-12 h-12 rounded-lg object-cover">
                <div>
                    <h5 class="font-bold text-sm text-gray-800">${item.name}</h5>
                    <p class="text-xs text-gray-500">${item.qty} x ${item.price} BDT</p>
                </div>
            </div>
            <span class="font-bold text-gray-700">${(item.price * item.qty).toFixed(0)} BDT</span>
        </div>
    `).join('');

    // total calculation
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const deliveryFee = 60;
    const total = subtotal + deliveryFee;

    document.getElementById('summarySubtotal').textContent = subtotal.toFixed(2) + ' BDT';
    document.getElementById('summaryTotal').textContent = total.toFixed(2) + ' BDT';
}

document.addEventListener('DOMContentLoaded', initCheckoutPage);