<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'PetShop')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐾</text></svg>">
    @stack('styles')
</head>
<body>
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
                <span class="amount" id="cartTotal">0.00 BDT</span>
            </div>
            <button class="btn btn-primary checkout-btn" onclick="alert('Proceeding to Checkout...')">
                Proceed to Checkout →
            </button>
        </div>
    </aside>

    <div id="toastContainer" class="toast-container"></div>

    @include('layouts.navigation')

    <main class="page-content">
        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>