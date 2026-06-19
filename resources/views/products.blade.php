@extends('layouts.app')

@section('title', 'PetShop — Products')

@section('content')
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
        <button class="filter-toggle-btn" id="filterToggle">
            <i class="fa-solid fa-sliders"></i> Filter By Category
        </button>
      </div>

      <div class="filter-drawer" id="filterDrawer">
        <h4 style="font-size: .85rem; font-weight: 700; text-transform: uppercase; color: var(--clr-text-muted); margin-bottom: 4px;">Category</h4>
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
            <input type="text" id="searchInput" placeholder="Search products in this list..." />
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
                        <a href="{{ route('products.show', $product->slug) }}">
                            <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" 
                                 style="width: 100%; height: 100%; object-fit: contain;"
                                 onerror="this.src='https://via.placeholder.com/220'">
                        </a>
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
                    <h3 style="margin-bottom:8px; color: var(--clr-text-muted);">No products found in this category</h3>
                    <a href="{{ route('products.index') }}" class="btn btn-outline">Clear Filters</a>
                </div>
            @endforelse
          </div>

          <div class="pagination-wrapper">
              {{ $products->appends(request()->query())->links() }}
          </div>
        </div>
      </div>

    </div>
  </div>
</main>
@endsection