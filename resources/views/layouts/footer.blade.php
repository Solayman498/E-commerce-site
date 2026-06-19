<footer class="footer">
  <div class="container">
    <div class="footer-flex-container">
      
      <div class="footer-brand-area">
        <a href="/" class="brand">
          <i class="fa-solid fa-paw text-blue-600"></i> PetShop
        </a>
        <p class="text-xs text-gray-400">Happy pets, happy life.</p>
      </div>

      <ul class="footer-links-inline">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('products.index') }}">Shop</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>

      <ul class="footer-links-inline">
        <li>
          <a href="#" target="_blank" rel="noopener" aria-label="Facebook">
            <i class="fa-brands fa-facebook text-base"></i> Facebook
          </a>
        </li>
        <li>
          <a href="#" target="_blank" rel="noopener" aria-label="Instagram">
            <i class="fa-brands fa-instagram text-base"></i> Instagram
          </a>
        </li>
      </ul>

      <div class="footer-bottom-compact text-gray-400">
        &copy; {{ date('Y') }} PetShop. All rights reserved.
      </div>

    </div>
  </div>
</footer>