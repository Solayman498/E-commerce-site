
@extends('layouts.app')

@section('title', 'PetShop — Products')

@section('content')
<!-- ═══════════════════════════════════════════════
     CART OVERLAY & SIDEBAR
════════════════════════════════════════════════ -->
<div class="cart-overlay" id="cartOverlay"></div>
<aside class="cart-sidebar" id="cartSidebar" aria-label="Shopping cart">
  <div class="cart-header">
    <div>
      <h3>🛒 Your Cart</h3>
      <div class="cart-header-meta" id="cartMeta">0 items</div>
    </div>
    <button class="close-btn" id="closeSidebarBtn" aria-label="Close cart">✕</button>
  </div>
  <div class="cart-items" id="cartItemsList"></div>
  <div class="cart-footer">
    <div class="cart-subtotal">
      <span class="label">Subtotal</span>
      <span class="amount" id="cartTotal">$0.00</span>
    </div>
    <button class="btn btn-primary checkout-btn" onclick="showToast('🚀 Checkout coming soon! (Connect your Laravel API)', 'success')">
      Proceed to Checkout →
    </button>
  </div>
</aside>



<!-- Mobile nav menu -->
<div class="mobile-nav" id="mobileNav">
  <a href="{{ route('home') }}">🏠 Home</a>
  <a href="{{ route('products.index') }}">🛍 Products</a>
  <a href="#categories">🗂 Categories</a>
  <a href="#">📦 Orders</a>
</div>

<!-- ═══════════════════════════════════════════════
     PAGE CONTENT
════════════════════════════════════════════════ -->
<main class="page-content">

  <!-- ── HERO ── -->
  <section class="hero">
    <div class="container">
      <div class="hero-text">
        <div class="hero-tag fade-in">🐾 Trusted by 10,000+ Pet Parents</div>
        <h1 class="fade-in fade-in-1">
          Give Your Pet<br>
          <span>The Life They</span><br>Deserve.
        </h1>
        <p class="fade-in fade-in-2">
          Premium food, toys, and accessories — all handpicked by vets and pet experts. Fast delivery. Happy pets guaranteed.
        </p>
        <div class="hero-cta fade-in fade-in-3">
          <a href="{{ route('products.index') }}" class="btn btn-primary">Shop Now →</a>
          <a href="#categories" class="btn btn-outline">Browse Categories</a>
        </div>
        <div class="hero-stats fade-in fade-in-4">
          <div class="hero-stat"><div class="num">10K+</div><div class="lbl">Happy Pets</div></div>
          <div class="hero-stat"><div class="num">500+</div><div class="lbl">Products</div></div>
          <div class="hero-stat"><div class="num">4.9★</div><div class="lbl">Avg Rating</div></div>
        </div>
      </div>
      <div class="hero-visual">
        <div class="hero-pet-card">
          🐶
          <div class="hero-bubble top-right">
            <span class="icon">🚚</span> Free delivery
          </div>
          <div class="hero-bubble bottom-left">
            <span class="icon">⭐</span> Vet approved
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── CATEGORIES ── -->
  <section class="section" id="categories">
    <div class="container">
      <div class="section-header">
        <div class="section-tag">Browse</div>
        <h2>Shop by Category</h2>
        <p>Find everything your pet needs, organized for easy discovery.</p>
      </div>
      <div class="categories-grid" id="categoriesGrid"></div>
    </div>
  </section>

  <!-- ── FEATURED PRODUCTS ── -->
  <section class="section section-alt">
    <div class="container">
      <div class="section-header">
        <div class="section-tag">Featured</div>
        <h2>Best Sellers</h2>
        <p>Our most-loved products — tried, tested, and tail-waggingly approved.</p>
      </div>
      <div class="products-grid" id="featuredGrid"></div>
      <div style="text-align:center; margin-top:36px;">
        <a href="{{ route('products.index') }}" class="btn btn-outline">View All Products →</a>
      </div>
    </div>
  </section>

  <!-- ── BANNER CTA ── -->
  <section class="section" style="background: linear-gradient(135deg, var(--clr-primary) 0%, #1a4a30 100%);">
    <div class="container" style="text-align:center; color:#fff;">
      <div style="font-size:3.5rem; margin-bottom:16px;">🎁</div>
      <h2 style="font-family:var(--font-display); font-size:2rem; margin-bottom:10px;">New member? Get 10% off!</h2>
      <p style="opacity:.8; margin-bottom:28px; max-width:420px; margin-left:auto; margin-right:auto;">
        Use code <strong>WELCOME10</strong> at checkout. Valid on your first order over $25.
      </p>
      <a href="{{ route('products.index') }}" class="btn btn-accent">Start Shopping →</a>
    </div>
  </section>

</main>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {

    // categories safe render
    const catGrid = document.getElementById('categoriesGrid');
    if (catGrid && typeof CATEGORIES !== "undefined") {
      catGrid.innerHTML = CATEGORIES.map(c => `
        <div class="category-card" onclick="window.location='products.html?cat=${c.name}'">
          <div class="category-icon">${c.emoji}</div>
          <div class="category-name">${c.name}</div>
          <div class="category-count">${c.count} products</div>
        </div>
      `).join('');
    }

    // featured safe render
    const featuredGrid = document.getElementById('featuredGrid');
    if (featuredGrid && typeof PRODUCTS !== "undefined") {
      const featured = [...PRODUCTS]
        .sort((a, b) => b.reviews - a.reviews)
        .slice(0, 8);

      featuredGrid.innerHTML = featured.map(productCardHTML).join('');
    }

  });
</script>
@endpush