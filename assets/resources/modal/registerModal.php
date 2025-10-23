<!-- ================================
     Customer Register Modal
     ================================ -->
<div id="registerModal" class="auth-modal">
  <div class="auth-modal-content">
    <button class="auth-close" onclick="closeRegisterModal()">&times;</button>

    <h2>Customer Registration</h2>

    <?php if (isset($_SESSION['register_error'])): ?>
      <p class="auth-error"><?php echo $_SESSION['register_error']; unset($_SESSION['register_error']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['register_success'])): ?>
      <p class="auth-success"><?php echo $_SESSION['register_success']; unset($_SESSION['register_success']); ?></p>
    <?php endif; ?>

    <form id="registerForm" method="POST" action="assets/resources/helpers/register.php">
      <label class="auth-label" for="regName">Full Name</label>
      <input class="auth-input" type="text" id="regName" name="name" required>

      <label class="auth-label" for="regUsername">Username</label>
      <input class="auth-input" type="text" id="regUsername" name="username" required>

      <label class="auth-label" for="regEmail">Email</label>
      <input class="auth-input" type="email" id="regEmail" name="email" required>

      <label class="auth-label" for="regPassword">Password</label>
      <input class="auth-input" type="password" id="regPassword" name="password" required>

      <label class="auth-label" for="regConfirm">Confirm Password</label>
      <input class="auth-input" type="password" id="regConfirm" name="confirm_password" required>

      <button type="submit" class="auth-btn">Register</button>
    </form>

    <p class="auth-switch">
      Already have an account?
      <a href="#" onclick="switchToLogin()">Login here</a>
    </p>
  </div>
</div>
