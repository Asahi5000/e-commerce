function removePhoto() {
    // Show modal instead of confirm()
    document.getElementById("removePhotoModal").style.display = "flex";
}

// Modal actions
document.getElementById("confirmRemove").addEventListener("click", function() {
    const preview = document.getElementById("profilePreview");
    const removePhotoInput = document.getElementById("remove_photo");

    preview.src = "../../assets/uploads/user/default.jpg";
    removePhotoInput.value = "1";

    document.getElementById("removePhotoModal").style.display = "none";
});

document.getElementById("cancelRemove").addEventListener("click", function() {
    document.getElementById("removePhotoModal").style.display = "none";
});
// Close modal when clicking outside content
document.getElementById("removePhotoModal").addEventListener("click", function(event) {
    if (event.target === this) {
        this.style.display = "none";
    }
});

// Close modal on ESC key
document.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
        const modal = document.getElementById("removePhotoModal");
        if (modal.style.display === "flex") {
            modal.style.display = "none";
        }
    }
});