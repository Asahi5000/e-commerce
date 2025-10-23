function openLoginModal() {
  document.getElementById("loginModal").style.display = "flex";
}

function closeLoginModal() {
  document.getElementById("loginModal").style.display = "none";
}

// Optional: close when clicking outside modal
window.addEventListener("click", function(e) {
  const modal = document.getElementById("loginModal");
  if (e.target === modal) {
    closeLoginModal();
  }
});