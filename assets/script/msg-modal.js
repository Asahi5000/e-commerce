document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const messageStatus = urlParams.get("message");

    const successModal = document.getElementById("successModal");
    const errorModal = document.getElementById("errorModal");

    let activeModal = null;
    let countdownElement = null;

    if (messageStatus === "success" && successModal) {
        successModal.style.display = "block";
        activeModal = successModal;
        countdownElement = document.getElementById("countdownSuccess");
    } else if (messageStatus === "error" && errorModal) {
        errorModal.style.display = "block";
        activeModal = errorModal;
        countdownElement = document.getElementById("countdownError");
    }

    if (activeModal && countdownElement) {
        let secondsLeft = 10;
        countdownElement.textContent = secondsLeft;

        const timer = setInterval(() => {
            secondsLeft--;
            countdownElement.textContent = secondsLeft;

            if (secondsLeft <= 0) {
                clearInterval(timer);
                closeModal(activeModal);
            }
        }, 1000);
    }

    // Close button click
    document.querySelectorAll(".close-btn").forEach(btn => {
        btn.onclick = function() {
            closeModal(btn.closest(".modal"));
        }
    });

    // Close on outside click
    window.onclick = function(event) {
        if (event.target.classList.contains("modal")) {
            closeModal(event.target);
        }
    }

    function closeModal(modal) {
        modal.querySelector(".modal-content").style.animation = "fadeOut 0.5s ease forwards";
        setTimeout(() => {
            modal.style.display = "none";
            window.history.replaceState({}, document.title, window.location.pathname);
        }, 500);
    }
});