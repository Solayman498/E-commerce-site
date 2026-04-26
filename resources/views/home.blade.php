@extends('layouts.app')

@section('title', 'PetShop — Premium Pet Supplies')

@section('content')

<div class="cart-overlay" id="cartOverlay"></div>
<aside class="cart-sidebar" id="cartSidebar" aria-label="Shopping cart">
  </aside>

<main class="page-content">

  <section class="hero">
    <div class="container">
      <div class="hero-text">
        <div class="hero-tag fade-in">🐾 Trusted by 10,000+ Pet Parents</div>
        <h1 class="fade-in fade-in-1">
          Give Your Pet<br>
          <span>The Life They</span><br>Deserve.
        </h1>
        <p class="fade-in fade-in-2">
          Premium food, toys, and accessories — all handpicked by vets and pet experts.
        </p>
        <div class="hero-cta fade-in fade-in-3">
          <a href="{{ route('products.index') }}" class="btn btn-primary">Shop Now →</a>
          <a href="#categories" class="btn btn-outline">Browse Categories</a>
        </div>
      </div>
      <div class="hero-visual">
        <div class="hero-pet-card">🐶</div>
      </div>
    </div>
  </section>

  <section class="section" id="categories">
    <div class="container">
      <div class="section-header">
        <div class="section-tag">Browse</div>
        <h2>Shop by Category</h2>
      </div>
      <div class="categories-grid">
        {{-- আমরা এখানে ক্যাটাগরিগুলো হার্ডকোড করতে পারি অথবা কন্ট্রোলার থেকে পাঠাতে পারি --}}
        @php
            $displayCategories = [
                ['name' => 'Dogs', 'emoji' => '🐕'],
                ['name' => 'Cats', 'emoji' => '🐈'],
                ['name' => 'Birds', 'emoji' => '🦜'],
                ['name' => 'Fish', 'emoji' => '🐠'],
            ];
        @endphp

        @foreach($displayCategories as $cat)
          <div class="category-card" onclick="window.location='{{ route('products.index', ['category' => $cat['name']]) }}'">
            <div class="category-icon">{{ $cat['emoji'] }}</div>
            <div class="category-name">{{ $cat['name'] }}</div>
            {{-- এখানে আপনি ক্যাটাগরি অনুযায়ী কাউন্টও দেখাতে পারেন --}}
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="section section-alt">
    <div class="container">
      <div class="section-header">
        <div class="section-tag">Featured</div>
        <h2>Best Sellers</h2>
        <p>Our most-loved products — tail-waggingly approved.</p>
      </div>
      
      <div class="products-grid">
        @forelse($featuredProducts as $product)
          <div class="product-card fade-in">
            <div class="product-img">
              @if($product->is_featured)
                <span class="product-badge hot">HOT</span>
              @endif
              <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" style="width:100%; height:100%; object-fit:contain;">
            </div>
            <div class="product-body">
              <div class="product-category">{{ $product->category }}</div>
              <div class="product-name">{{ $product->name }}</div>
              <div class="product-footer">
                <div class="product-price">{{ $product->price }} BDT</div>
                <button class="add-cart-btn" 
                    onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->image }}')">
                    +
                </button>
              </div>
            </div>
          </div>
        @empty
          <p>No featured products available.</p>
        @endforelse
      </div>

      <div style="text-align:center; margin-top:36px;">
        <a href="{{ route('products.index') }}" class="btn btn-outline">View All Products →</a>
      </div>
    </div>
  </section>

</main>

@endsection

@push('scripts')
<script>
    // এখানে আর আগের CATEGORIES বা PRODUCTS রেন্ডারিং লজিক লাগবে না। 
    // কারণ ব্লেড ফাইল এখন সার্ভার থেকেই ডাটা রেন্ডার করে দিচ্ছে।
</script>
@endpush