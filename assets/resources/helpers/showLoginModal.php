<?php if (isset($_SESSION['show_login_modal']) && $_SESSION['show_login_modal']): ?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    openLoginModal();
  });
</script>
<?php 
  unset($_SESSION['show_login_modal']); 
endif; ?>