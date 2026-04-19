/**
 * PetShop — app.js
 * Core application logic: auth, cart, UI, toast, theme
 * Ready to connect to a Laravel API backend later.
 * ─────────────────────────────────────────────────────
 */

/* ============================================================
   PRODUCT DATA  (replace with API call to Laravel later)
   ============================================================ */
const PRODUCTS = [
  { id:1,  name:'Premium Dog Kibble',     category:'Dog',    price:24.99, oldPrice:29.99, emoji:'🦮', badge:'sale',  rating:4.8, reviews:312 },
  { id:2,  name:'Grain-Free Cat Food',    category:'Cat',    price:18.49, oldPrice:null,  emoji:'🐱', badge:'new',   rating:4.6, reviews:187 },
  { id:3,  name:'Interactive Feather Wand',category:'Cat',   price:9.99,  oldPrice:null,  emoji:'🪶', badge:null,    rating:4.4, reviews:95  },
  { id:4,  name:'Chew-Proof Dog Toy',     category:'Dog',    price:14.99, oldPrice:null,  emoji:'🦴', badge:'hot',   rating:4.9, reviews:428 },
  { id:5,  name:'Budgie Seed Mix',        category:'Birds',  price:7.49,  oldPrice:null,  emoji:'🐦', badge:null,    rating:4.3, reviews:63  },
  { id:6,  name:'Hamster Wheel Pro',      category:'Small',  price:16.99, oldPrice:19.99, emoji:'🐹', badge:'sale',  rating:4.5, reviews:142 },
  { id:7,  name:'Aquarium Starter Kit',   category:'Fish',   price:44.99, oldPrice:null,  emoji:'🐠', badge:'new',   rating:4.7, reviews:88  },
  { id:8,  name:'Calming Dog Bed',        category:'Dog',    price:39.99, oldPrice:49.99, emoji:'🛏',  badge:'sale',  rating:4.9, reviews:356 },
  { id:9,  name:'Cat Scratching Post',    category:'Cat',    price:22.99, oldPrice:null,  emoji:'🐈', badge:null,    rating:4.5, reviews:211 },
  { id:10, name:'Reptile Heat Lamp',      category:'Reptile',price:18.99, oldPrice:null,  emoji:'🦎', badge:null,    rating:4.2, reviews:47  },
  { id:11, name:'Dog Grooming Kit',       category:'Dog',    price:27.99, oldPrice:34.99, emoji:'✂️', badge:'sale',  rating:4.6, reviews:193 },
  { id:12, name:'Catnip Mouse Toy',       category:'Cat',    price:5.99,  oldPrice:null,  emoji:'🐭', badge:null,    rating:4.7, reviews:504 },
];

const CATEGORIES = [
  { name:'Dogs',    emoji:'🐕', count: 38 },
  { name:'Cats',    emoji:'🐈', count: 45 },
  { name:'Birds',   emoji:'🦜', count: 22 },
  { name:'Fish',    emoji:'🐠', count: 17 },
  { name:'Small',   emoji:'🐹', count: 14 },
  { name:'Reptile', emoji:'🦎', count: 9  },
];

/* ============================================================
   CART STATE
   ============================================================ */
let cart = JSON.parse(localStorage.getItem('ps_cart') || '[]');

function saveCart() {
  localStorage.setItem('ps_cart', JSON.stringify(cart));
}

function getCartTotal() {
  return cart.reduce((sum, item) => sum + item.price * item.qty, 0).toFixed(2);
}

function getCartCount() {
  return cart.reduce((n, item) => n + item.qty, 0);
}

function addToCart(productId) {
  const product = PRODUCTS.find(p => p.id === productId);
  if (!product) return;
  const existing = cart.find(i => i.id === productId);
  if (existing) {
    existing.qty++;
  } else {
    cart.push({ id: product.id, name: product.name, price: product.price, emoji: product.emoji, qty: 1 });
  }
  saveCart();
  updateCartUI();
  showToast(`${product.emoji} <strong>${product.name}</strong> added to cart`, 'success');
}

function removeFromCart(productId) {
  cart = cart.filter(i => i.id !== productId);
  saveCart();
  updateCartUI();
  renderCartItems();
}

function updateQty(productId, delta) {
  const item = cart.find(i => i.id === productId);
  if (!item) return;
  item.qty = Math.max(0, item.qty + delta);
  if (item.qty === 0) removeFromCart(productId);
  else {
    saveCart();
    updateCartUI();
    renderCartItems();
  }
}

/* ============================================================
   CART UI
   ============================================================ */
function updateCartUI() {
  const count = getCartCount();
  document.querySelectorAll('.cart-count').forEach(el => {
    el.textContent = count;
    el.classList.toggle('show', count > 0);
  });
  // Update total in sidebar
  const totalEl = document.getElementById('cartTotal');
  if (totalEl) totalEl.textContent = '$' + getCartTotal();
  // Update item count text
  const metaEl = document.getElementById('cartMeta');
  if (metaEl) metaEl.textContent = count + (count === 1 ? ' item' : ' items');
}

function renderCartItems() {
  const container = document.getElementById('cartItemsList');
  if (!container) return;
  if (cart.length === 0) {
    container.innerHTML = `
      <div class="cart-empty">
        <div class="cart-empty-icon">🛒</div>
        <p>Your cart is empty.<br>Add some items for your pet!</p>
        <button class="btn btn-outline" onclick="closeSidebar()" style="margin-top:8px;">Browse Products</button>
      </div>`;
    return;
  }
  container.innerHTML = cart.map(item => `
    <div class="cart-item" id="ci-${item.id}">
      <div class="cart-item-img">${item.emoji}</div>
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-price">$${(item.price * item.qty).toFixed(2)}</div>
        <div class="cart-item-controls">
          <button class="qty-btn" onclick="updateQty(${item.id}, -1)">−</button>
          <span class="qty-val">${item.qty}</span>
          <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
        </div>
      </div>
      <button class="remove-item" onclick="removeFromCart(${item.id})" aria-label="Remove">🗑</button>
    </div>`).join('');
}

function openSidebar() {
  document.getElementById('cartSidebar')?.classList.add('show');
  document.getElementById('cartOverlay')?.classList.add('show');
  document.body.style.overflow = 'hidden';
  renderCartItems();
}

function closeSidebar() {
  document.getElementById('cartSidebar')?.classList.remove('show');
  document.getElementById('cartOverlay')?.classList.remove('show');
  document.body.style.overflow = '';
}

/* ============================================================
   TOAST SYSTEM
   ============================================================ */
function showToast(message, type = 'success') {
  const container = document.getElementById('toastContainer');
  if (!container) return;
  const id   = 'toast-' + Date.now();
  const icon = type === 'success' ? '✅' : '❌';
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.id = id;
  toast.innerHTML = `<span class="toast-icon">${icon}</span><span class="toast-msg">${message}</span>`;
  container.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add('show'));
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 400);
  }, 3000);
}

/* ============================================================
   DARK MODE
   ============================================================ */
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

// Apply saved theme immediately
(function() {
  const saved = localStorage.getItem('ps_theme');
  if (saved) document.documentElement.setAttribute('data-theme', saved);
})();

/* ============================================================
   PROFILE DROPDOWN
   ============================================================ */
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

function initUserDisplay() {
  const user = JSON.parse(localStorage.getItem('ps_user') || 'null');
  if (!user) return;
  document.querySelectorAll('.user-name-display').forEach(el => el.textContent = user.name);
  document.querySelectorAll('.user-email-display').forEach(el => el.textContent = user.email);
  document.querySelectorAll('.user-avatar').forEach(el => el.textContent = user.avatar || user.name[0].toUpperCase());
}

function logout() {
  localStorage.removeItem('ps_user');
  localStorage.removeItem('ps_cart');
  window.location.href = 'login.html';
}

/* ============================================================
   HAMBURGER MOBILE NAV
   ============================================================ */
function initMobileNav() {
  const ham    = document.getElementById('hamburger');
  const mobile = document.getElementById('mobileNav');
  if (!ham || !mobile) return;
  ham.addEventListener('click', () => {
    ham.classList.toggle('open');
    mobile.classList.toggle('show');
  });
}

/* ============================================================
   PRODUCT RENDERING (used on home + products pages)
   ============================================================ */
function productCardHTML(p) {
  const stars = '★'.repeat(Math.floor(p.rating)) + (p.rating % 1 ? '½' : '');
  return `
    <div class="product-card fade-in">
      <div class="product-img">
        ${p.badge ? `<span class="product-badge ${p.badge}">${p.badge.toUpperCase()}</span>` : ''}
        <span>${p.emoji}</span>
      </div>
      <div class="product-body">
        <div class="product-category">${p.category}</div>
        <div class="product-name">${p.name}</div>
        <div class="product-rating">
          <span class="stars">${stars}</span>
          <span class="rating-count">(${p.reviews})</span>
        </div>
        <div class="product-footer">
          <div class="product-price">
            $${p.price.toFixed(2)}
            ${p.oldPrice ? `<span class="old">$${p.oldPrice.toFixed(2)}</span>` : ''}
          </div>
          <button class="add-cart-btn" onclick="addToCart(${p.id})" aria-label="Add to cart">+</button>
        </div>
      </div>
    </div>`;
}

/* ============================================================
   INIT — runs on every page
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
  // Apply saved theme
  const savedTheme = localStorage.getItem('ps_theme') || 'light';
  applyTheme(savedTheme);

  // Theme toggle button
  document.getElementById('themeToggle')?.addEventListener('click', toggleTheme);

  // Cart button
  document.getElementById('cartBtn')?.addEventListener('click', openSidebar);
  document.getElementById('cartOverlay')?.addEventListener('click', closeSidebar);
  document.getElementById('closeSidebarBtn')?.addEventListener('click', closeSidebar);

  // Init UI
  initProfileDropdown();
  initUserDisplay();
  initMobileNav();
  updateCartUI();

  // Mark active nav link
  const path = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a, .mobile-nav a').forEach(a => {
    if (a.getAttribute('href') === path) a.classList.add('active');
  });
});
