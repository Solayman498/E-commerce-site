@extends('layouts.app')

@section('title', 'PetShop — Products')

@push('styles')
  <style>
  
    .filter-mobile-bar { display: none; margin-bottom: 16px; }
    .filter-toggle-btn { display: flex; align-items: center; gap: 8px; padding: 10px 18px; background: var(--clr-surface); border: 1.5px solid var(--clr-border); border-radius: var(--r-sm); font-size: .88rem; font-weight: 600; }
    .filter-drawer { background: var(--clr-surface); border: 1px solid var(--clr-border); border-radius: var(--r-lg); padding: 20px; display: none; flex-direction: column; gap: 20px; margin-bottom: 16px; }
    .filter-drawer.open { display: flex; }
    @media (max-width: 1024px) { .products-sidebar { display: none; } .filter-mobile-bar { display: flex; } }
  </style>
@endpush

@section('content')
<aside class="cart-sidebar" id="cartSidebar">
    </aside>

<main class="page-content">
  <div class="page-banner">
    <div class="container">
      <h1>🛍 All Products</h1>
      <p>Over 500 vet-approved items for your pet family.</p>
    </div>
  </div>

  <div class="section">
    <div class="container">
      <div class="filter-mobile-bar">
        <button class="filter-toggle-btn" id="filterToggle">🔧 Filters</button>
      </div>

      <div class="products-page-layout">
        <aside class="products-sidebar">
            <div class="filter-group">
                <h4>Category</h4>
                <div class="filter-chip-list">
                    <a href="{{ route('products.index') }}" class="filter-chip {{ !request('category') ? 'active' : '' }}">All</a>
                    @foreach(['Dog', 'Cat', 'Birds', 'Fish'] as $cat)
                        <a href="{{ route('products.index', ['category' => $cat]) }}" 
                           class="filter-chip {{ request('category') == $cat ? 'active' : '' }}">
                           {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>
            </aside>

        <div>
          <div class="search-bar">
            <span class="icon">🔍</span>
            <input type="text" id="searchInput" placeholder="Search products..." />
          </div>

          <div class="products-main-header">
            <div class="products-count">Showing {{ $products->count() }} products</div>
          </div>

          <div class="products-grid" id="productsGrid">
            @forelse($products as $product)
                <div class="product-card fade-in">
                    <div class="product-img">
                        @if($product->is_featured)
                            <span class="product-badge hot">HOT</span>
                        @endif
                        <img src="{{ $product->image ? asset('storage/products/' . $product->image) : asset('images/default-pet.png') }}" 
                             alt="{{ $product->name }}" 
                             style="width: 100%; height: 100%; object-fit: contain;">
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
                <div id="emptyState" style="text-align:center; padding:60px 20px; grid-column: 1/-1;">
                    <h3 style="margin-bottom:8px;">No products found</h3>
                    <a href="{{ route('products.index') }}" class="btn btn-outline">Clear Filters</a>
                </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
    // cart logic
    document.getElementById('filterToggle')?.addEventListener('click', () => {
        document.getElementById('filterDrawer')?.classList.toggle('open');
    });

    // search logic
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            let name = card.querySelector('.product-name').textContent.toLowerCase();
            card.style.display = name.includes(value) ? 'block' : 'none';
        });
    });
</script>
@endpush