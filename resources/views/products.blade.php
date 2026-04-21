@extends('layouts.app')

@section('title', 'PetShop — Products')

@push('styles')
  <style>
    /* Extra styles for products page layout */
    .filter-mobile-bar {
      display: none; margin-bottom: 16px;
    }
    .filter-toggle-btn {
      display: flex; align-items: center; gap: 8px;
      padding: 10px 18px; background: var(--clr-surface);
      border: 1.5px solid var(--clr-border); border-radius: var(--r-sm);
      font-size: .88rem; font-weight: 600;
    }
    .filter-drawer {
      background: var(--clr-surface); border: 1px solid var(--clr-border);
      border-radius: var(--r-lg); padding: 20px;
      display: none; flex-direction: column; gap: 20px;
      margin-bottom: 16px;
    }
    .filter-drawer.open { display: flex; }
    @media (max-width: 1024px) {
      .products-sidebar { display: none; }
      .filter-mobile-bar { display: flex; }
    }
  </style>
@endpush
@section('content')

<!-- Cart overlay & sidebar (same as index) -->
<div class="cart-overlay" id="cartOverlay"></div>
<aside class="cart-sidebar" id="cartSidebar">
  <div class="cart-header">
    <div>
      <h3>🛒 Your Cart</h3>
      <div class="cart-header-meta" id="cartMeta">0 items</div>
    </div>
    <button class="close-btn" id="closeSidebarBtn">✕</button>
  </div>
  <div class="cart-items" id="cartItemsList"></div>
  <div class="cart-footer">
    <div class="cart-subtotal">
      <span class="label">Subtotal</span>
      <span class="amount" id="cartTotal">$0.00</span>
    </div>
    <button class="btn btn-primary checkout-btn" onclick="showToast('🚀 Checkout coming soon!', 'success')">
      Proceed to Checkout →
    </button>
  </div>
</aside>




<main class="page-content">
  <!-- Page Banner -->
  <div class="page-banner">
    <div class="container">
      <h1>🛍 All Products</h1>
      <p>Over 500 vet-approved items for your furry, feathered & scaly family.</p>
    </div>
  </div>

  <div class="section">
    <div class="container">

      <!-- Mobile filter toggle -->
      <div class="filter-mobile-bar">
        <button class="filter-toggle-btn" id="filterToggle">🔧 Filters</button>
      </div>

      <!-- Mobile filter drawer -->
      <div class="filter-drawer" id="filterDrawer"></div>

      <div class="products-page-layout">

        <!-- SIDEBAR (desktop) -->
        <aside class="products-sidebar" id="desktopSidebar"></aside>

        <!-- MAIN -->
        <div>
          <!-- Search -->
          <div class="search-bar">
            <span class="icon">🔍</span>
            <input type="text" id="searchInput" placeholder="Search products… e.g. dog food, cat toy" />
          </div>

          <!-- Header row -->
          <div class="products-main-header">
            <div class="products-count" id="productsCount">Showing all products</div>
            <select class="sort-select" id="sortSelect">
              <option value="popular">Most Popular</option>
              <option value="price-asc">Price: Low → High</option>
              <option value="price-desc">Price: High → Low</option>
              <option value="rating">Highest Rated</option>
              <option value="name">Name A–Z</option>
            </select>
          </div>

          <!-- Products grid -->
          <div class="products-grid" id="productsGrid"></div>

          <!-- Empty state -->
          <div id="emptyState" style="display:none; text-align:center; padding:60px 20px;">
            <div style="font-size:4rem; margin-bottom:16px; opacity:.3;">🔍</div>
            <h3 style="margin-bottom:8px;">No products found</h3>
            <p style="color:var(--clr-text-muted);">Try adjusting your search or filters.</p>
            <button class="btn btn-outline" onclick="resetFilters()" style="margin-top:16px;">Clear Filters</button>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

@endsection
@push('scripts')
<script>
  /* ── Products Page Logic ── */

  // State
  let activeCategory = 'All';
  let maxPrice       = 50;
  let searchQuery    = '';
  let sortBy         = 'popular';

  // Read URL param (e.g. ?cat=Dogs from category click on home)
  const urlCat = new URLSearchParams(window.location.search).get('cat');
  if (urlCat) activeCategory = urlCat;

  // Sidebar HTML builder
  function buildFiltersHTML() {
    const cats = ['All', ...new Set(PRODUCTS.map(p => p.category))];
    return `
      <div class="filter-group">
        <h4>Category</h4>
        <div class="filter-chip-list">
          ${cats.map(c => `
            <button class="filter-chip ${activeCategory === c ? 'active' : ''}"
              onclick="setCategory('${c}')">${c}</button>`).join('')}
        </div>
      </div>
      <div class="filter-group">
        <h4>Max Price</h4>
        <div class="price-range">
          <input type="range" min="5" max="50" value="${maxPrice}" step="1"
            oninput="setPrice(this.value)" id="priceRange" />
          <div class="price-display"><span>$0</span><span id="priceLabel">$${maxPrice}</span></div>
        </div>
      </div>
      <div class="filter-group">
        <h4>Badge</h4>
        <div class="filter-chip-list">
          <button class="filter-chip" onclick="filterBadge('new')">🆕 New</button>
          <button class="filter-chip" onclick="filterBadge('sale')">🏷 Sale</button>
          <button class="filter-chip" onclick="filterBadge('hot')">🔥 Hot</button>
        </div>
      </div>
      <button class="btn btn-ghost" style="width:100%; margin-top:8px;" onclick="resetFilters()">✕ Clear Filters</button>
    `;
  }

  function renderSidebars() {
    const html = buildFiltersHTML();
    const desktop = document.getElementById('desktopSidebar');
    const drawer  = document.getElementById('filterDrawer');
    if (desktop) desktop.innerHTML = html;
    if (drawer)  drawer.innerHTML  = html;
  }

  function setCategory(cat) {
    activeCategory = cat;
    renderSidebars();
    renderProducts();
  }

  function setPrice(val) {
    maxPrice = Number(val);
    document.querySelectorAll('#priceLabel').forEach(el => el.textContent = '$' + val);
    renderProducts();
  }

  function filterBadge(badge) {
    // Toggle: filter by badge
    const filtered = PRODUCTS.filter(p => p.badge === badge);
    renderGrid(filtered);
  }

  function resetFilters() {
    activeCategory = 'All';
    maxPrice       = 50;
    searchQuery    = '';
    sortBy         = 'popular';
    document.getElementById('searchInput').value   = '';
    document.getElementById('sortSelect').value    = 'popular';
    renderSidebars();
    renderProducts();
  }

  function getFiltered() {
    let list = [...PRODUCTS];
    if (activeCategory !== 'All') list = list.filter(p => p.category === activeCategory);
    list = list.filter(p => p.price <= maxPrice);
    if (searchQuery) {
      const q = searchQuery.toLowerCase();
      list = list.filter(p => p.name.toLowerCase().includes(q) || p.category.toLowerCase().includes(q));
    }
    // Sort
    if (sortBy === 'price-asc')  list.sort((a, b) => a.price - b.price);
    if (sortBy === 'price-desc') list.sort((a, b) => b.price - a.price);
    if (sortBy === 'rating')     list.sort((a, b) => b.rating - a.rating);
    if (sortBy === 'name')       list.sort((a, b) => a.name.localeCompare(b.name));
    if (sortBy === 'popular')    list.sort((a, b) => b.reviews - a.reviews);
    return list;
  }

  function renderGrid(list) {
    const grid  = document.getElementById('productsGrid');
    const empty = document.getElementById('emptyState');
    const count = document.getElementById('productsCount');
    if (list.length === 0) {
      grid.innerHTML  = '';
      empty.style.display = 'block';
    } else {
      empty.style.display = 'none';
      grid.innerHTML = list.map(productCardHTML).join('');
    }
    count.textContent = `Showing ${list.length} product${list.length !== 1 ? 's' : ''}`;
  }

  function renderProducts() {
    renderGrid(getFiltered());
  }

  // Event listeners
  document.getElementById('searchInput').addEventListener('input', e => {
    searchQuery = e.target.value.trim();
    renderProducts();
  });

  document.getElementById('sortSelect').addEventListener('change', e => {
    sortBy = e.target.value;
    renderProducts();
  });

  document.getElementById('filterToggle').addEventListener('click', () => {
    document.getElementById('filterDrawer').classList.toggle('open');
  });

  // Initial render
  renderSidebars();
  renderProducts();
</script>
@endpush
