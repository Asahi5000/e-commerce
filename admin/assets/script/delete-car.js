document.addEventListener("DOMContentLoaded", function() {
    const deleteButtons = document.querySelectorAll(".deleteBtn");
    const deleteModal = document.getElementById("deleteCarModal");
    const deleteOverlay = document.getElementById("edit-delete-overlay");
    const closeDeleteModal = document.getElementById("closeDeleteModal");
    const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
    const deleteCarId = document.getElementById("deleteCarId");
    const deleteCarName = document.getElementById("deleteCarName");

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.dataset.id;
            const name = this.dataset.name;

            deleteCarId.value = id;
            deleteCarName.textContent = name;

            deleteModal.style.display = "block";
            deleteOverlay.style.display = "block";
        });
    });

    function closeModal() {
        deleteModal.style.display = "none";
        deleteOverlay.style.display = "none";
    }

    closeDeleteModal.addEventListener("click", closeModal);
    cancelDeleteBtn.addEventListener("click", closeModal);
    deleteOverlay.addEventListener("click", closeModal);
});