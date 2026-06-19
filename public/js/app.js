
// 1. State Management

let cart = JSON.parse(localStorage.getItem('ps_cart') || '[]');


// 2. Responsive Hamburger Menu & Profile Dropdown

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

        if (wrap && wrap.classList.contains('open')) {
            if (!wrap.contains(e.target)) {
                wrap.classList.remove('open');
            }
        }
    });
}


// 3. Cart Logic

function addToCart(id, name, price, image) {
    const existing = cart.find(i => i.id === id);
    if (existing) existing.qty++;
    else cart.push({ id, name, price, image, qty: 1 });
    
    localStorage.setItem('ps_cart', JSON.stringify(cart));
    updateCartUI();
   
}


// 4. Cart Sidebar Rendering

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
                    <button class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                    <span class="qty-val">${item.qty}</span>
                    <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                </div>
            </div>
            <button onclick="removeFromCart(${item.id})" style="color:red; background:none; border:none; cursor:pointer;">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `).join('');
}


// 5. Cart UI Update
function updateCartUI() {
    const count = cart.reduce((n, item) => n + item.qty, 0);
    document.querySelectorAll('.cart-count').forEach(el => {
        el.textContent = count;
        if (count > 0) {
            el.classList.add('show');
            el.style.display = 'inline-flex';
        } else {
            el.classList.remove('show');
            el.style.display = 'none';
        }
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


// 6. Initialization (DOMContentLoaded)

document.addEventListener('DOMContentLoaded', () => {

    document.documentElement.setAttribute('data-theme', 'light');
    const themeBtn = document.getElementById('themeToggle');
    if (themeBtn) {
        themeBtn.style.display = 'none'; 
    }


    const cartBtn = document.getElementById('cartBtn');
    if (cartBtn) {
        cartBtn.addEventListener('click', (e) => { 
            e.preventDefault(); 
            openSidebar(); 
        });
    }
    
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', closeSidebar);
    }
    
    const cartOverlay = document.getElementById('cartOverlay');
    if (cartOverlay) {
        cartOverlay.addEventListener('click', closeSidebar);
    }


    if (window.location.pathname.includes('/bkash/success')) {
        localStorage.removeItem('ps_cart');
        cart = [];
    }


    initResponsiveUI();
    updateCartUI();
});


// 7. Order Placement Function

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
            if (paymentMethod === 'bkash' && result.payment_url) {
                window.location.href = result.payment_url;
            } else {
                localStorage.removeItem('ps_cart');
                cart = [];
                if (typeof updateCartUI === "function") updateCartUI();
                window.location.href = '/orders';
            }
        }
    } catch (error) {
        if(btn) { btn.disabled = false; btn.innerText = 'Confirm Order'; }
        console.error('Error placing order:', error);
    }
}


// 8. Checkout Page Initialization

function initCheckoutPage() {
    const summaryContainer = document.getElementById('checkoutSummaryItems');
    if (!summaryContainer) return; 
    if (cart.length === 0) {
        window.location.href = '/'; 
        return;
    }

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

    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const deliveryFee = 60;
    const total = subtotal + deliveryFee;

    const subtotalEl = document.getElementById('summarySubtotal');
    const totalEl = document.getElementById('summaryTotal');
    
    if (subtotalEl) subtotalEl.textContent = subtotal.toFixed(2) + ' BDT';
    if (totalEl) totalEl.textContent = total.toFixed(2) + ' BDT';
}

if (document.getElementById('checkoutSummaryItems')) {
    document.addEventListener('DOMContentLoaded', initCheckoutPage);
}