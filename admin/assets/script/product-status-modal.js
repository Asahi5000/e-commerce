// assets/script/product-status-modal.js
document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);

  // Support both old & new query params
  let status = urlParams.get("status");
  let message = urlParams.get("message");

  if (!status) {
    // fallback for old style query params
    if (urlParams.get("success")) {
      status = "success";
      message = "Product added successfully!";
    } else if (urlParams.get("error")) {
      status = "error";
      message = urlParams.get("error");
    } else if (urlParams.get("edited")) {
      status = "edited";
      message = "Product updated successfully!";
    } else if (urlParams.get("deleted")) {
      status = "deleted";
      message = "Product deleted successfully!";
    }
  }

  const overlay = document.getElementById("statusModalOverlay");
  const modal = document.getElementById("statusModal");
  const modalMessage = document.getElementById("statusMessage");
  const timerEl = document.getElementById("statusTimer");
  const closeBtn = document.getElementById("statusClose");

  if (status) {
    overlay.style.display = "flex";

    if (status === "success") {
      modal.classList.add("success");
    } else if (status === "error") {
      modal.classList.add("error");
    } else if (status === "edited") {
      modal.classList.add("edited");
    } else if (status === "deleted") {
      modal.classList.add("deleted");
    }

    modalMessage.textContent = message || "Operation completed successfully!";

    let seconds = 10;
    timerEl.textContent = `Closing in ${seconds} seconds...`;

    const countdown = setInterval(() => {
      seconds--;
      if (seconds > 0) {
        timerEl.textContent = `Closing in ${seconds} seconds...`;
      } else {
        clearInterval(countdown);
        overlay.style.display = "none";
        window.history.replaceState({}, document.title, window.location.pathname);
      }
    }, 1000);

    closeBtn.addEventListener("click", function () {
      clearInterval(countdown);
      overlay.style.display = "none";
      window.history.replaceState({}, document.title, window.location.pathname);
    });
  }
});
