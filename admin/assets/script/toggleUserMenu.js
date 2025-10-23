function toggleUserMenu() {
  const menu = document.getElementById("userMenu");
  menu.style.display = (menu.style.display === "flex") ? "none" : "flex";
}

// ✅ Close when clicking outside
document.addEventListener("click", function(event) {
  const menu = document.getElementById("userMenu");
  const userInfo = document.querySelector(".user-info");

  if (menu.style.display === "flex" && !userInfo.contains(event.target)) {
    menu.style.display = "none";
  }
});

// ✅ Close when pressing ESC
document.addEventListener("keydown", function(event) {
  if (event.key === "Escape") {
    const menu = document.getElementById("userMenu");
    if (menu.style.display === "flex") {
      menu.style.display = "none";
    }
  }
});