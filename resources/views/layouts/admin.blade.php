<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PetShop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <div class="admin-container">
        <div class="sidebar">
            <h2><i class="fa-solid fa-paw"></i> PetShop Admin</h2>
            <hr>
            
            <a href="{{ route('admin.products.index') }}">
                <i class="fa-solid fa-box"></i> Products Management
            </a>
            <a href="{{ route('admin.orders.index') }}">
                <i class="fa-solid fa-truck-fast"></i> Order Tracking
            </a>
            <a href="/">
                <i class="fa-solid fa-arrow-left"></i> Back to Website
            </a>

            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                @csrf
            </form>
            
            <button class="btn-logout-white" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </div>

        <div class="main-content">
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: var(--r-sm); border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            @yield('admin_content')
        </div>
    </div>

</body>
</html>