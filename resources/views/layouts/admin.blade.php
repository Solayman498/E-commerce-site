<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PetShop</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #2c3e50; color: white; padding: 20px; }
        .sidebar a { display: block; color: #bdc3c7; padding: 10px 0; text-decoration: none; }
        .sidebar a:hover { color: white; }
        .main-content { flex: 1; padding: 30px; background: #f4f7f6; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        .btn { padding: 8px 15px; border-radius: 5px; cursor: pointer; border: none; text-decoration: none; display: inline-block; }
        .btn-add { background: #2ecc71; color: white; }
        .btn-edit { background: #3498db; color: white; margin-right: 5px; }
        .btn-delete { background: #e74c3c; color: white; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>🐾 PetShop Admin</h2>
            <hr>
            <a href="{{ route('admin.products.index') }}"> Products Management</a>
            <a href="{{ route('admin.orders.index') }}">Order Tracking</a>
            <a href="/"> Back to Website</a>
            <a href="#" onclick="alert('Logout logic later')"> Logout</a>
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
</body>
</html>