/* Login modal functions */
function openLoginModal() {
  document.getElementById("loginModal").style.display = "flex";
  document.body.classList.add("auth-body-locked");
}
function closeLoginModal() {
  document.getElementById("loginModal").style.display = "none";
  document.body.classList.remove("auth-body-locked");
}

/* Register modal functions */
function openRegisterModal() {
  document.getElementById("registerModal").style.display = "flex";
  document.body.classList.add("auth-body-locked");
}
function closeRegisterModal() {
  document.getElementById("registerModal").style.display = "none";
  document.body.classList.remove("auth-body-locked");
}

/* Switching between login <-> register */
function switchToRegister() {
  closeLoginModal();
  openRegisterModal();
}
function switchToLogin() {
  closeRegisterModal();
  openLoginModal();
}