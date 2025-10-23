let currentCarId = null; // ✅ Store selected car ID globally

function openModal(name, brand, year, price, image, description, category, mileage, transmission, fuel, stock, carId) {
  currentCarId = carId; // Save selected car ID

  // ✅ Fill modal info
  document.getElementById("modalName").textContent = name;
  document.getElementById("modalBrandYear").textContent = `${brand} • ${year}`;
  document.getElementById("modalPrice").textContent = `₱${parseFloat(price).toLocaleString("en-PH", {
    minimumFractionDigits: 2
  })}`;

  document.getElementById("modalCategory").textContent = `Category: ${category}`;
  document.getElementById("modalMileage").textContent = `Mileage: ${mileage} km`;
  document.getElementById("modalTransmission").textContent = `Transmission: ${transmission}`;
  document.getElementById("modalFuel").textContent = `Fuel: ${fuel}`;
  document.getElementById("modalStock").textContent = `Stock: ${stock}`;
  document.getElementById("modalDescription").textContent = description;

  // ✅ Load image (fallback to default)
  const modalImage = document.getElementById("modalImage");
  modalImage.src = image || "assets/uploads/car/default.png";
  modalImage.onerror = () => (modalImage.src = "assets/uploads/car/default.png");

  // ✅ Store car ID in Purchase button
  const purchaseBtn = document.getElementById("purchaseBtn");
  purchaseBtn.dataset.carId = carId;

  // ✅ Show modal
  document.getElementById("carModal").classList.add("show");
}

function closeModal() {
  document.getElementById("carModal").classList.remove("show");
}

// ✅ Close when clicking outside modal
document.getElementById("carModal").addEventListener("click", (e) => {
  if (e.target.classList.contains("view-details-modal")) closeModal();
});

// ✅ Handle Purchase Button click
document.getElementById("purchaseBtn").addEventListener("click", function () {
  const carId = this.dataset.carId || currentCarId;

  if (!carId) {
    alert("⚠️ No car selected.");
    return;
  }

  closeModal(); // Close details modal first
  openPurchaseModal(carId); // Then open purchase modal
});
