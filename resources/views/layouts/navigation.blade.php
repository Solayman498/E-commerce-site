<nav class="navbar" role="navigation">
  <div class="container">

    <a href="{{ route('home') }}" class="brand">
      <div class="brand-icon">🐾</div>
      PetShop
    </a>

    <ul class="nav-links">
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="{{ route('products.index') }}">Products</a></li>
      <li><a href="#categories">Categories</a></li>
      <li><a href="#">Orders</a></li>
    </ul>

    <div class="nav-actions">
        <button class="btn theme-toggle" id="themeToggle">🌙</button>
        <button class="btn cart-btn" id="cartBtn">
            🛒 <span class="badge cart-count" id="cartCount">0</span>
        </button>

        @auth
            <div class="profile-wrap" id="profileWrap">
                <button class="profile-btn">
                    <div class="profile-avatar user-avatar">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                        @else
                            {{ substr(Auth::user()->name, 0, 1) }}
                        @endif
                    </div>
                    <span class="profile-name user-name-display">{{ Auth::user()->name }}</span>
                    <span class="chevron">▾</span>
                </button>
                
                <div class="dropdown-menu">
                    <div class="dropdown-header">
                        <div class="avatar-lg user-avatar">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
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

        @guest
            <a href="{{ route('login') }}" class="btn btn-outline" style="margin-left: 10px;">Login</a>
        @endguest

        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </div>
  </div>
</nav>