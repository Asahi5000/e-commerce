let autoCloseTimer, countdownTimer;

function showModal(message) {
  document.getElementById("errorText").innerText = message;
  document.getElementById("errorModal").style.display = "flex";

  // Start countdown
  let countdown = 10;
  document.getElementById("countdown").innerText = countdown;

  countdownTimer = setInterval(() => {
    countdown--;
    document.getElementById("countdown").innerText = countdown;
    if (countdown <= 0) {
      clearInterval(countdownTimer);
    }
  }, 1000);

  // Auto close after 5 seconds
  autoCloseTimer = setTimeout(() => {
    closeModal();
  }, 10000);
}

function closeModal() {
  document.getElementById("errorModal").style.display = "none";
  clearTimeout(autoCloseTimer);
  clearInterval(countdownTimer);
}

window.onload = function() {
  if (typeof window.errorMessage !== "undefined" && window.errorMessage !== "") {
    showModal(window.errorMessage);
  }
};
