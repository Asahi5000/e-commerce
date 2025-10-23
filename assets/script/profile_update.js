document.addEventListener("DOMContentLoaded", () => {
  const profileForm = document.getElementById("profileForm");
  const profilePreview = document.getElementById("profilePreview");
  const profileMsg = document.getElementById("profileMessage");

  if (!profileForm) return;

  profileForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(profileForm);
    profileMsg.textContent = "⏳ Saving changes...";
    profileMsg.style.color = "#fbc02d";

    const baseUrl = `${window.location.origin}/${window.location.pathname.split('/')[1]}`;

    try {
      const res = await fetch(`${baseUrl}/assets/resources/helpers/update-profile.php`, {
        method: "POST",
        body: formData
      });

      const data = await res.json();

      if (data.status === "success") {
        profileMsg.textContent = "✅ Profile updated successfully!";
        profileMsg.style.color = "#4caf50";

        const timestamp = new Date().getTime();

        if (data.profile_image && profilePreview) {
          profilePreview.src = `${baseUrl}/admin/assets/uploads/user/${data.profile_image}?v=${timestamp}`;
        }

        const userAvatar = document.querySelector(".user-avatar");
        if (userAvatar && data.profile_image) {
          userAvatar.src = `${baseUrl}/admin/assets/uploads/user/${data.profile_image}?v=${timestamp}`;
        }

      } else {
        profileMsg.textContent = data.message || "⚠️ Update failed.";
        profileMsg.style.color = "red";
      }
    } catch (err) {
      console.error(err);
      profileMsg.textContent = "⚠️ Network error. Try again.";
      profileMsg.style.color = "red";
    }
  });
});
