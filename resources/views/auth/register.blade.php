<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PetShop — Create Account</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐾</text></svg>">
</head>
<body>
  <main class="auth-page">

    <!-- Left: Visual Panel -->
    <aside class="auth-visual">
      <div class="auth-visual-content">
        <div class="auth-visual-emoji">🐶</div>
        <h2>Join thousands of happy pet parents.</h2>
        <p>Create your free account and start exploring premium pet products today.</p>
        <ul class="auth-features">
          <li class="auth-feature"><span class="auth-feature-icon">🎁</span> 10% off your first order</li>
          <li class="auth-feature"><span class="auth-feature-icon">📦</span> Track all your orders easily</li>
          <li class="auth-feature"><span class="auth-feature-icon">❤️</span> Save your favourite products</li>
          <li class="auth-feature"><span class="auth-feature-icon">🔔</span> Get deals & restocking alerts</li>
        </ul>
      </div>
    </aside>

    <!-- Right: Register Form -->
    <section class="auth-form-side">
      <div class="auth-card">

        <div class="auth-header">
          <span class="brand">
            <div class="brand-icon">🐾</div>
            PetShop
          </span>
          <h1>Create your account</h1>
          <p>It's free and only takes a minute.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
            @csrf

            <div class="form-row">
                <div class="field-group">
                    <label for="firstName">First name</label>
                    <div class="field-wrap">
                        <input type="text" name="first_name" id="firstName" value="{{ old('first_name') }}" placeholder="John" required />
                    </div>
                    @error('first_name') <span class="error-msg show">{{ $message }}</span> @enderror
                </div>
                <div class="field-group">
                    <label for="lastName">Last name</label>
                    <div class="field-wrap">
                        <input type="text" name="last_name" id="lastName" value="{{ old('last_name') }}" placeholder="Doe" required />
                    </div>
                    @error('last_name') <span class="error-msg show">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="field-group">
                <label for="email">Email address</label>
                <div class="field-wrap">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="you@example.com" required />
                </div>
                @error('email') <span class="error-msg show">{{ $message }}</span> @enderror
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <div class="field-wrap">
                    <input type="password" name="password" id="password" placeholder="Min. 8 characters" required />
                    <button type="button" class="toggle-pw" data-target="password">👁</button>
                </div>
                @error('password') <span class="error-msg show">{{ $message }}</span> @enderror
            </div>

            <div class="field-group">
                <label for="confirmPw">Confirm password</label>
                <div class="field-wrap">
                    <input type="password" name="password_confirmation" id="confirmPw" placeholder="Repeat your password" required />
                    <button type="button" class="toggle-pw" data-target="confirmPw">👁</button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <span>Create Account</span> <span>→</span>
            </button>
        </form>

        <p class="auth-switch" style="margin-top:24px;">
          Already have an account? <a href="{{ route('login') }}">Log In</a>
        </p>

      </div>
    </section>
  </main>

  <div class="toast-container" id="toastContainer"></div>

  <script src="{{ asset('js/app.js') }}"></script>

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
