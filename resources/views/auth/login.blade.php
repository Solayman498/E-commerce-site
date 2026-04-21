<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PetShop — Login</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐾</text></svg>">
</head>
<body>
  <!-- ─── AUTH PAGE LAYOUT ─── -->
  <main class="auth-page">

    <!-- Left: Visual Panel -->
    <aside class="auth-visual">
      <div class="auth-visual-content">
        <div class="auth-visual-emoji">🐾</div>
        <h2>Your pets deserve<br>the very best.</h2>
        <p>Premium food, accessories, and toys — delivered to your door with love.</p>
        <ul class="auth-features">
          <li class="auth-feature"><span class="auth-feature-icon">🚚</span> Free delivery on orders over $40</li>
          <li class="auth-feature"><span class="auth-feature-icon">⭐</span> 10,000+ happy pet parents</li>
          <li class="auth-feature"><span class="auth-feature-icon">🔒</span> Secure, trusted checkout</li>
          <li class="auth-feature"><span class="auth-feature-icon">💚</span> Vet-approved products only</li>
        </ul>
      </div>
    </aside>

    <!-- Right: Login Form -->
    <section class="auth-form-side">
      <div class="auth-card">

        <div class="auth-header">
          <a href="index.html" class="brand">
            <div class="brand-icon">🐾</div>
            PetShop
          </a>
          <h1>Welcome back!</h1>
          <p>Sign in to continue shopping for your furry friends.</p>
        </div>


        @if(session('error'))
          <p style="color:red;">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form" id="loginForm">
            @csrf

            <div class="field-group">
                <label for="email">Email address</label>
                <div class="field-wrap">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="you@example.com" required />
                </div>
                @error('email')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <div class="field-wrap">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required />
                    <button type="button" class="toggle-pw" aria-label="Toggle password visibility" data-target="password">👁</button>
                </div>
                @error('password')
                    <span class="error-msg show">{{ $message }}</span>
                @enderror
            </div>

            @if(session('error'))
                <div class="error-msg show" style="text-align: center; margin-bottom: 10px;">
                    {{ session('error') }}
                </div>
            @endif

            <div style="text-align:right; margin-top:-8px;">
                <a href="#" style="font-size:.85rem; color:var(--clr-primary); font-weight:500;">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary" id="loginBtn">
                <span>Log In</span> <span>→</span>
            </button>
        </form>

        <p class="auth-switch" style="margin-top:24px;">
          New to PetShop? <a href="/register">Create an account</a>
        </p>

      </div>
    </section>
  </main>

  <!-- Toast container -->
  <div class="toast-container" id="toastContainer"></div>

  <script src="app.js"></script>
  <script>
    

    // Password toggle
    document.querySelectorAll('.toggle-pw').forEach(btn => {
      btn.addEventListener('click', () => {
        const inp = document.getElementById(btn.dataset.target);
        inp.type = inp.type === 'password' ? 'text' : 'password';
        btn.textContent = inp.type === 'password' ? '👁' : '🙈';
      });
    });

  </script>
</body>
</html>
