document.addEventListener("DOMContentLoaded", () => {
  const purchaseModal = document.getElementById("purchaseModal");
  const purchaseForm = document.getElementById("purchaseForm");
  const paymentStatus = document.getElementById("paymentStatus");
  const carIdInput = document.getElementById("car_id");
  const carPriceInput = document.getElementById("carPrice");

  const custName = document.getElementById("custName");
  const custEmail = document.getElementById("custEmail");
  const custPhone = document.getElementById("custPhone");
  const custAddress = document.getElementById("custAddress");

  async function fetchCustomerInfo() {
    try {
      const res = await fetch("assets/resources/helpers/get_customer_info.php");
      const data = await res.json();
      if (data.success) {
        const u = data.data;
        custName.textContent = u.name || "—";
        custEmail.textContent = u.email || "—";
        custPhone.textContent = u.phone || "—";
        custAddress.textContent = u.address || "—";
      }
    } catch (err) {
      console.error("Error fetching customer info:", err);
    }
  }

  if (purchaseForm) {
    purchaseForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      paymentStatus.textContent = "⏳ Processing payment...";
      paymentStatus.style.color = "#ffcc00";

      const formData = new FormData(purchaseForm);

      try {
        const res = await fetch("assets/resources/helpers/purchase_process.php", {
          method: "POST",
          body: formData,
        });
        const data = await res.json();

        if (data.success) {
          paymentStatus.style.color = "#4CAF50";
          paymentStatus.textContent = data.message;
          setTimeout(() => closePurchaseModal(), 4000);
        } else {
          paymentStatus.style.color = "red";
          paymentStatus.textContent = data.message;
        }
      } catch (err) {
        console.error(err);
        paymentStatus.style.color = "red";
        paymentStatus.textContent = "⚠️ Error processing payment.";
      }
    });
  }

  window.closePurchaseModal = function () {
    purchaseModal.style.display = "none";
    purchaseForm.reset();
    paymentStatus.textContent = "";
  };

  window.openPurchaseModal = async function (carId) {
    if (!purchaseModal || !carIdInput) {
      console.error("❌ Purchase modal or car_id input not found in DOM");
      return;
    }

    try {
      const res = await fetch(`assets/resources/helpers/get_car_details.php?car_id=${carId}`);
      const data = await res.json();

      if (data.success) {
        const car = data.car;
        carIdInput.value = car.car_id;
        carPriceInput.value = car.price;

        purchaseModal.style.display = "flex";
        purchaseModal.setAttribute("aria-hidden", "false");
        await fetchCustomerInfo();
      } else {
        alert("❌ Car not found.");
      }
    } catch (err) {
      console.error("Error:", err);
      alert("⚠️ Could not load car details.");
    }
  };
});
