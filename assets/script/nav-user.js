document.addEventListener("DOMContentLoaded", function () {
  const userToggle = document.querySelector(".user-toggle");
  const userDropdown = document.querySelector(".nav-user-dropdown");

  if (userToggle) {
    userToggle.addEventListener("click", function (e) {
      e.preventDefault();
      userDropdown.classList.toggle("open");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
      if (!userDropdown.contains(e.target)) {
        userDropdown.classList.remove("open");
      }
    });
  }
});