<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PetShop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        .admin-container { display: flex; min-height: 100vh; position: relative; }
        
        .sidebar { width: 250px; background: #2c3e50; color: white; padding: 20px; transition: all 0.3s ease; z-index: 1000; }
        .sidebar h2 { font-size: 20px; margin-bottom: 15px; }
        .sidebar hr { border: 0; border-top: 1px solid #34495e; margin-bottom: 15px; }
        .sidebar a { display: block; color: #bdc3c7; padding: 12px 10px; text-decoration: none; border-radius: 5px; margin-bottom: 5px; transition: 0.2s; }
        .sidebar a:hover { color: white; background: #34495e; }
        
        .main-content { flex: 1; padding: 30px; background: #f4f7f6; width: 100%; transition: all 0.3s ease; }
        
        .mobile-header { display: none; background: #2c3e50; color: white; padding: 15px 20px; justify-content: space-between; items-center: center; }
        .menu-toggle-btn { background: none; border: none; color: white; font-size: 22px; cursor: pointer; }

        @media (max-width: 768px) {
            .admin-container { flex-direction: column; }
            
            .mobile-header { display: flex; position: sticky; top: 0; z-index: 1001; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
            
            .sidebar { 
                position: fixed; 
                top: 0; 
                left: -250px; 
                height: 100vh; 
                box-shadow: 5px 0 15px rgba(0,0,0,0.3); 
            }
            
            .sidebar.active { left: 0; }
            
            .main-content { padding: 20px; }
        }

        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="mobile-header">
        <h2><i class="fa-solid fa-paw"></i> PetShop</h2>
        <button class="menu-toggle-btn" id="menuToggle">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <div class="admin-container">
        <div class="sidebar" id="sidebarMenu">
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
            <a href="#" onclick="alert('Logout logic later')">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>

        <div class="main-content">
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                    {{ session('success') }}
                </div>
            @endif

            @yield('admin_content')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebarMenu = document.getElementById('sidebarMenu');

            if(menuToggle && sidebarMenu) {
                menuToggle.addEventListener('click', function(e) {
                    sidebarMenu.classList.toggle('active');
                    e.stopPropagation(); 
                });

                document.addEventListener('click', function(e) {
                    if (!sidebarMenu.contains(e.target) && sidebarMenu.classList.contains('active')) {
                        sidebarMenu.classList.remove('active');
                    }
                });
            }
        });
    </script>
</body>
</html>