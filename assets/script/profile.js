// assets/script/profile-modal.js
document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("profileModal");
  const form = document.getElementById("profileForm");
  const closeBtn = modal.querySelector(".close-btn");

  // --- Open Profile Modal ---
  window.openProfileModal = function () {
    modal.style.display = "flex";
    modal.setAttribute("data-open", "true");
    modal.setAttribute("aria-hidden", "false");
  };

  // --- Close Profile Modal ---
  function closeModal() {
    modal.style.display = "none";
    modal.removeAttribute("data-open");
    modal.setAttribute("aria-hidden", "true");
  }

  closeBtn.addEventListener("click", closeModal);
  modal.addEventListener("click", (e) => { if (e.target === modal) closeModal(); });
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.getAttribute("data-open") === "true") closeModal();
  });

  // --- AJAX Form Submit ---
  if (form) {
    const messageEl = document.createElement("p");
    messageEl.className = "profile-message";
    form.appendChild(messageEl);

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      messageEl.textContent = "⏳ Saving changes...";
      messageEl.className = "profile-message text-warning";

      try {
        const formData = new FormData(form);
        const res = await fetch("assets/resources/helpers/update-profile.php", {
          method: "POST",
          body: formData,
          credentials: "same-origin"
        });
        const data = await res.json();

        if (data.status === "success") {
          messageEl.textContent = "✅ Profile updated successfully!";
          messageEl.className = "profile-message text-success";

          // 🧩 Automatically update purchase modal user info
          const nameEl = document.querySelector("#purchaseModal .customer-info p:nth-child(1)");
          const emailEl = document.querySelector("#purchaseModal .customer-info p:nth-child(2)");
          const phoneEl = document.querySelector("#purchaseModal .customer-info p:nth-child(3)");
          const addressEl = document.querySelector("#purchaseModal .customer-info p:nth-child(4)");

          if (nameEl && data.name) nameEl.innerHTML = `<strong>Name:</strong> ${data.name}`;
          if (emailEl && form.querySelector("[name='email']"))
            emailEl.innerHTML = `<strong>Email:</strong> ${form.querySelector("[name='email']").value}`;
          if (phoneEl && form.querySelector("[name='phone']"))
            phoneEl.innerHTML = `<strong>Phone:</strong> ${form.querySelector("[name='phone']").value}`;
          if (addressEl && form.querySelector("[name='address']"))
            addressEl.innerHTML = `<strong>Address:</strong> ${form.querySelector("[name='address']").value}`;

          // 🕒 Close modal after 1.5s
          setTimeout(closeModal, 1500);
        } else {
          messageEl.textContent = "❌ " + (data.message || "Failed to update profile.");
          messageEl.className = "profile-message text-error";
        }
      } catch (err) {
        messageEl.textContent = "⚠️ Error saving profile. Please try again.";
        messageEl.className = "profile-message text-error";
        console.error(err);
      }
    });
  }
});
