// --- Apply saved theme ASAP before render ---
(function () {
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark") {
    document.documentElement.classList.add("dark-mode");
  } else {
    document.documentElement.classList.remove("dark-mode");
  }
})();

document.addEventListener("DOMContentLoaded", function () {
  const themeBtn = document.querySelector(".theme-toggle");
  if (!themeBtn) return;

  // Set correct button state on load
  if (document.documentElement.classList.contains("dark-mode")) {
    themeBtn.textContent = "🌙";
  } else {
    themeBtn.textContent = "☀️";
  }

  // --- Theme Toggle ---
  function toggleTheme() {
    document.documentElement.classList.toggle("dark-mode");
    const isDark = document.documentElement.classList.contains("dark-mode");
    themeBtn.textContent = isDark ? "🌙" : "☀️";
    localStorage.setItem("theme", isDark ? "dark" : "light");
  }

  themeBtn.addEventListener("click", function (e) {
    e.preventDefault(); // avoid accidental page reloads
    toggleTheme();
  });
});
