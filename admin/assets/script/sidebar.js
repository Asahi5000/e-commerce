document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.querySelector('.sidebar');
  const hamburger = document.querySelector('.hamburger');
  if (!sidebar || !hamburger) return;

  function openSidebar() {
    sidebar.classList.remove('hidden');
    localStorage.setItem('sidebar', 'open');
  }
  function closeSidebar() {
    sidebar.classList.add('hidden');
    localStorage.setItem('sidebar', 'closed');
  }
  function toggleSidebar() {
    if (sidebar.classList.contains('hidden')) openSidebar();
    else closeSidebar();
  }

  // Restore sidebar state for desktop
  function initSidebar() {
    if (window.innerWidth > 992) {
      const saved = localStorage.getItem('sidebar');
      if (saved === 'open') openSidebar();
      else closeSidebar();
    } else {
      closeSidebar(); // mobile always starts closed
    }
  }
  initSidebar();

  // Hamburger toggle
  hamburger.addEventListener('click', function (e) {
    e.stopPropagation();
    toggleSidebar();
  });

  // Prevent inside clicks from bubbling
  sidebar.addEventListener('click', function (e) {
    e.stopPropagation();
  });

  // Close sidebar if clicked outside (mobile only)
  document.addEventListener('click', function () {
    if (window.innerWidth <= 992 && !sidebar.classList.contains('hidden')) {
      closeSidebar();
    }
  });

  // Auto-close when clicking nav menu links (mobile only)
  document.querySelectorAll('.sidebar a').forEach(link => {
    link.addEventListener('click', e => {
      if (window.innerWidth <= 992) {
        closeSidebar();
      }
      e.stopPropagation(); // prevent double toggle blink
    });
  });

  // Re-check when resizing screen
  window.addEventListener('resize', initSidebar);
});
