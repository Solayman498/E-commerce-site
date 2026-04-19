<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PetShop — Create Account</title>
  <link rel="stylesheet" href="style.css" />
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
          <a href="index.html" class="brand">
            <div class="brand-icon">🐾</div>
            PetShop
          </a>
          <h1>Create your account</h1>
          <p>It's free and only takes a minute.</p>
        </div>

        <form class="auth-form" id="registerForm" novalidate>

          <!-- Name row -->
          <div class="form-row">
            <div class="field-group">
              <label for="firstName">First name</label>
              <div class="field-wrap">
                <input type="text" id="firstName" placeholder="John" autocomplete="given-name" />
              </div>
              <span class="error-msg" id="firstErr">Required.</span>
            </div>
            <div class="field-group">
              <label for="lastName">Last name</label>
              <div class="field-wrap">
                <input type="text" id="lastName" placeholder="Doe" autocomplete="family-name" />
              </div>
              <span class="error-msg" id="lastErr">Required.</span>
            </div>
          </div>

          <!-- Email -->
          <div class="field-group">
            <label for="email">Email address</label>
            <div class="field-wrap">
              <input type="email" id="email" placeholder="you@example.com" autocomplete="email" />
            </div>
            <span class="error-msg" id="emailErr">Please enter a valid email.</span>
          </div>

          <!-- Password -->
          <div class="field-group">
            <label for="password">Password</label>
            <div class="field-wrap">
              <input type="password" id="password" placeholder="Min. 6 characters" autocomplete="new-password" />
              <button type="button" class="toggle-pw" data-target="password">👁</button>
            </div>
            <span class="error-msg" id="passErr">Password must be at least 6 characters.</span>
          </div>

          <!-- Confirm Password -->
          <div class="field-group">
            <label for="confirmPw">Confirm password</label>
            <div class="field-wrap">
              <input type="password" id="confirmPw" placeholder="Repeat your password" autocomplete="new-password" />
              <button type="button" class="toggle-pw" data-target="confirmPw">👁</button>
            </div>
            <span class="error-msg" id="confirmErr">Passwords do not match.</span>
          </div>

          <!-- Terms -->
          <div class="field-group" style="flex-direction:row; align-items:flex-start; gap:10px;">
            <input type="checkbox" id="terms" style="margin-top:3px; accent-color:var(--clr-primary); width:16px; height:16px; flex-shrink:0;" />
            <label for="terms" style="font-size:.85rem; cursor:pointer; color:var(--clr-text-muted);">
              I agree to the <a href="#" style="color:var(--clr-primary);">Terms of Service</a> and
              <a href="#" style="color:var(--clr-primary);">Privacy Policy</a>
            </label>
          </div>
          <span class="error-msg" id="termsErr">You must accept the terms.</span>

          <!-- Submit -->
          <button type="submit" class="btn btn-primary">
            <span>Create Account</span> <span>→</span>
          </button>

        </form>

        <p class="auth-switch" style="margin-top:24px;">
          Already have an account? <a href="login.html">Sign in</a>
        </p>

      </div>
    </section>
  </main>

  <div class="toast-container" id="toastContainer"></div>

  <script src="app.js"></script>
  <script>
    // ── Register page script ──
    if (localStorage.getItem('ps_user')) window.location.href = 'index.html';

    // Password toggle
    document.querySelectorAll('.toggle-pw').forEach(btn => {
      btn.addEventListener('click', () => {
        const inp = document.getElementById(btn.dataset.target);
        inp.type  = inp.type === 'password' ? 'text' : 'password';
        btn.textContent = inp.type === 'password' ? '👁' : '🙈';
      });
    });

    function validateEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
    function setErr(id, inputEl, show) {
      document.getElementById(id).classList.toggle('show', show);
      if (inputEl) inputEl.classList.toggle('error', show);
    }

    document.getElementById('registerForm').addEventListener('submit', e => {
      e.preventDefault();
      const first   = document.getElementById('firstName');
      const last    = document.getElementById('lastName');
      const email   = document.getElementById('email');
      const pass    = document.getElementById('password');
      const confirm = document.getElementById('confirmPw');
      const terms   = document.getElementById('terms');
      let valid = true;

      if (!first.value.trim())        { setErr('firstErr',   first,   true); valid=false; } else setErr('firstErr',   first,   false);
      if (!last.value.trim())         { setErr('lastErr',    last,    true); valid=false; } else setErr('lastErr',    last,    false);
      if (!validateEmail(email.value)){ setErr('emailErr',   email,   true); valid=false; } else setErr('emailErr',   email,   false);
      if (pass.value.length < 6)      { setErr('passErr',    pass,    true); valid=false; } else setErr('passErr',    pass,    false);
      if (pass.value !== confirm.value){ setErr('confirmErr', confirm, true); valid=false; } else setErr('confirmErr', confirm, false);
      if (!terms.checked)             { setErr('termsErr',   null,    true); valid=false; } else setErr('termsErr',   null,    false);

      if (!valid) return;

      const user = {
        name:   first.value.trim() + ' ' + last.value.trim(),
        email:  email.value.trim(),
        avatar: first.value.trim()[0].toUpperCase(),
      };
      localStorage.setItem('ps_user', JSON.stringify(user));
      showToast('🎉 Account created! Welcome, ' + first.value + '!', 'success');
      setTimeout(() => window.location.href = 'index.html', 1200);
    });
  </script>
</body>
</html>
