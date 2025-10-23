document.addEventListener("DOMContentLoaded", () => {
  const modal = document.querySelector(".dropdown-modal");
  const openBtn = document.querySelector("#openProfileModal");
  const closeBtn = document.querySelector(".close-btn");
  const profileForm = document.querySelector("#profileForm");
  const profileImageInput = document.querySelector("#profile_image");
  const previewImage = document.querySelector("#profilePreview");
  const messageBox = document.querySelector(".profile-message");

  // ✅ Open modal
  openBtn?.addEventListener("click", () => {
    modal.style.display = "flex";
    modal.dataset.open = "true";
  });

  // ✅ Close modal
  closeBtn?.addEventListener("click", () => {
    modal.style.display = "none";
    modal.dataset.open = "false";
  });

  // ✅ Live image preview
  profileImageInput?.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (file) {
      previewImage.src = URL.createObjectURL(file);
    }
  });

  // ✅ Handle form submit (Ajax)
  profileForm?.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(profileForm);

    try {
      const res = await fetch("assets/resources/helpers/update-profile.php", {
        method: "POST",
        body: formData,
      });

      if (!res.ok) throw new Error("Network error");

      const data = await res.json();
      console.log("Server response:", data);

      if (data.status === "success") {
        messageBox.textContent = data.message;
        messageBox.className = "profile-message text-success";

        const timestamp = Date.now();
        const newImagePath = `admin/assets/uploads/user/${data.profile_image}?v=${timestamp}`;

        // ✅ Update image everywhere instantly
        document.querySelectorAll(".user-profile-img").forEach((img) => {
          img.src = newImagePath;
        });
      } else {
        messageBox.textContent = data.message;
        messageBox.className = "profile-message text-warning";
      }
    } catch (err) {
      console.error("Error:", err);
      messageBox.textContent = "Something went wrong.";
      messageBox.className = "profile-message text-error";
    }
  });
});
