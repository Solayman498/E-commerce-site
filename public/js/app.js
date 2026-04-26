// ১. স্টেট ম্যানেজমেন্ট
let cart = JSON.parse(localStorage.getItem('ps_cart') || '[]');

// ২. থিম ম্যানেজমেন্ট (Dark Mode)
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

// ৩. প্রোফাইল ড্রপডাউন
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

// ৪. কার্ট লজিক (আগের মতোই থাকবে)
function addToCart(id, name, price, image) {
    const existing = cart.find(i => i.id === id);
    if (existing) existing.qty++;
    else cart.push({ id, name, price, image, qty: 1 });
    
    localStorage.setItem('ps_cart', JSON.stringify(cart));
    updateCartUI();
    openSidebar();
}

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
    const item = cart.find(i => i.id === id);
    if (item) {
        item.qty += delta;
        if (item.qty <= 0) cart = cart.filter(i => i.id !== id);
        localStorage.setItem('ps_cart', JSON.stringify(cart));
        updateCartUI();
        renderCartItems();
    }
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    localStorage.setItem('ps_cart', JSON.stringify(cart));
    updateCartUI();
    renderCartItems();
}

// ৫. ইনাইটালাইজেশন (DOMContentLoaded)
document.addEventListener('DOMContentLoaded', () => {
    // থিম সেটআপ
    const savedTheme = localStorage.getItem('ps_theme') || 'light';
    applyTheme(savedTheme);
    document.getElementById('themeToggle')?.addEventListener('click', toggleTheme);

    // কার্ট ইভেন্ট
    document.getElementById('cartBtn')?.addEventListener('click', (e) => { e.preventDefault(); openSidebar(); });
    document.getElementById('closeSidebarBtn')?.addEventListener('click', closeSidebar);
    document.getElementById('cartOverlay')?.addEventListener('click', closeSidebar);

    // অন্যান্য UI
    initProfileDropdown();
    updateCartUI();
});