document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("profileModal");
    const closeBtn = modal.querySelector(".close-btn");

    // ✅ Open Profile Modal function
    window.openProfileModal = function () {
        fetch("customer/includes/modals/profileModal.php") // adjust the path
            .then(response => response.text())
            .then(html => {
                modal.innerHTML = html;
                modal.style.display = "flex";
                modal.setAttribute("data-open", "true");
                modal.setAttribute("aria-hidden", "false");

                // Reattach close button events after content reload
                const newCloseBtn = modal.querySelector(".close-btn");
                newCloseBtn.addEventListener("click", closeProfileModal);
            })
            .catch(error => {
                console.error("Error loading profile modal:", error);
            });
    };

    // ✅ Close modal function
    function closeProfileModal() {
        modal.style.display = "none";
        modal.removeAttribute("data-open");
        modal.setAttribute("aria-hidden", "true");
    }

    // Close on click outside
    modal.addEventListener("click", (e) => {
        if (e.target === modal) closeProfileModal();
    });

    // Close on ESC
    document.addEventListener("keydown", (e) => {
        if ((e.key === "Escape" || e.key === "Esc") && modal.getAttribute("data-open") === "true") {
            closeProfileModal();
        }
    });
});
