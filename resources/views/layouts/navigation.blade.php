<!-- ═══════════════════════════════════════════════
     NAVBAR
════════════════════════════════════════════════ -->
<nav class="navbar" role="navigation">
  <div class="container">

    <!-- Brand -->
    <a href="{{ route('home') }}" class="brand">
      <div class="brand-icon">🐾</div>
      PetShop
    </a>

    <!-- Center links -->
    <ul class="nav-links">
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="{{ route('products.index') }}">Products</a></li>
      <li><a href="#categories">Categories</a></li>
      <li><a href="#">Orders</a></li>
    </ul>

    <!-- Right actions -->
    <div class="nav-actions">

    <div class="nav-actions">
        <button class="btn theme-toggle" id="themeToggle">🌙</button>
        <button class="btn cart-btn" id="cartBtn">
            🛒 <span class="badge cart-count" id="cartCount">0</span>
        </button>

        @auth
            <div class="profile-wrap" id="profileWrap">
                <button class="profile-btn">
                    <div class="profile-avatar user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <span class="profile-name user-name-display">{{ Auth::user()->name }}</span>
                    <span class="chevron">▾</span>
                </button>
                
                <div class="dropdown-menu">
                    <div class="dropdown-header">
                        <div class="avatar-lg user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        <div class="user-info">
                            <div class="name user-name-display">{{ Auth::user()->name }}</div>
                            <div class="email user-email-display">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="dropdown-body">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">⚙️ Manage Profile</a>
                        <a href="#" class="dropdown-item">📦 My Orders</a>
                        <a href="#" class="dropdown-item">❤️ Wishlist</a>
                        <div class="dropdown-divider"></div>
                        
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                            @csrf
                        </form>
                        <button class="dropdown-item danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            🚪 Logout
                        </button>
                    </div>
                </div>
            </div>
        @endauth
      <!-- Hamburger (mobile) -->
      <button class="hamburger" id="hamburger" aria-label="Toggle menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</nav>