const hamburger = document.querySelector('.hamburger');
const navRight = document.querySelector('.nav-right');
const overlay = document.querySelector('.nav-overlay');

hamburger.addEventListener('click', () => {
  navRight.classList.toggle('active');
  overlay.classList.toggle('active');
});

overlay.addEventListener('click', () => {
  navRight.classList.remove('active');
  overlay.classList.remove('active');
});