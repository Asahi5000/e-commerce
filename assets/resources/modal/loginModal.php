<!-- ================================
     Customer Login Modal
     ================================ -->
<div id="loginModal" class="auth-modal">
  <div class="auth-modal-content">
    <button class="auth-close" onclick="closeLoginModal()">&times;</button>

    <h2>Customer Login</h2>

    <?php if (isset($_SESSION['login_error'])): ?>
      <p class="auth-error"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="assets/resources/helpers/login.php">
      <label class="auth-label" for="loginEmail">Email</label>
      <input class="auth-input" type="email" id="loginEmail" name="email" required>

      <label class="auth-label" for="loginPassword">Password</label>
      <input class="auth-input" type="password" id="loginPassword" name="password" required>

      <button type="submit" class="auth-btn">Login</button>
    </form>

    <p class="auth-switch">
      Don’t have an account?
      <a href="#" onclick="switchToRegister()">Register here</a>
    </p>
  </div>
</div>
