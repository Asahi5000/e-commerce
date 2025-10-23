document.addEventListener("DOMContentLoaded", () => {
    const editModal = document.getElementById("editCarModal");
    const closeBtns = [document.getElementById("closeEditModalBtn"), document.getElementById("closeEditModalBtn2")];
    const editButtons = document.querySelectorAll(".editBtn");

    // Open modal & fill data
    editButtons.forEach(button => {
        button.addEventListener("click", () => {
            document.getElementById("edit_car_id").value = button.dataset.id;
            document.getElementById("edit_name").value = button.dataset.name;
            document.getElementById("edit_brand").value = button.dataset.brand;
            document.getElementById("edit_model_year").value = button.dataset.model_year;
            document.getElementById("edit_category_id").value = button.dataset.category_id;
            document.getElementById("edit_price").value = button.dataset.price;
            document.getElementById("edit_mileage").value = button.dataset.mileage;
            document.getElementById("edit_transmission").value = button.dataset.transmission;
            document.getElementById("edit_fuel_type").value = button.dataset.fuel_type;
            document.getElementById("edit_stock").value = button.dataset.stock;
            document.getElementById("edit_description").value = button.dataset.description;

            editModal.style.display = "block";
        });
    });

    // Close modal
    closeBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            editModal.style.display = "none";
        });
    });

    // Close when clicking outside
    window.addEventListener("click", e => {
        if (e.target === editModal) {
            editModal.style.display = "none";
        }
    });
});