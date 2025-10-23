document.addEventListener("DOMContentLoaded", () => {
  const ordersModal = document.getElementById("ordersModal");
  const ordersList = document.getElementById("ordersList");

  // ✅ Helper function: return styled badge depending on status
  function getStatusBadge(status) {
    const s = status.toLowerCase();
    let color = "";
    let emoji = "";

    switch (s) {
      case "pending":
        color = "#ffcc00";
        emoji = "⏳";
        break;
      case "shipped":
        color = "#0099ff";
        emoji = "🚚";
        break;
      case "delivered":
        color = "#51fdb8ff";
        emoji = "✅";
        break;
      case "cancelled":
        color = "#fa9191ff";
        emoji = "❌";
        break;
      default:
        color = "#d4af37";
        emoji = "📦";
    }

    return `<span class="order-status" style="
      background: ${color};
      color: #111;
      font-weight: bold;
      padding: 8px 14px;
      border-radius: 6px;
      box-shadow: 0 0 8px rgba(212,175,55,0.3);
    ">${emoji} ${status}</span>`;
  }

  // ✅ Fetch all orders
  async function fetchOrders() {
    try {
      ordersList.innerHTML = `<p class="loading-text">⏳ Loading your orders...</p>`;
      const res = await fetch("assets/resources/helpers/get_orders.php");
      const data = await res.json();

      if (!data.success) {
        ordersList.innerHTML = `<p class="error-text">${data.message}</p>`;
        return;
      }

      if (!data.orders.length) {
        ordersList.innerHTML = `<p class="empty-text">You have no orders yet.</p>`;
        return;
      }

      ordersList.innerHTML = data.orders
        .map(
          (order) => `
        <div class="order-card">
          <div class="order-header">
            <h3>Order #${order.order_id}</h3>
            ${getStatusBadge(order.status)}
          </div>
          <p><strong>Total:</strong> ₱${parseFloat(order.total_amount).toLocaleString(
            "en-PH",
            { minimumFractionDigits: 2 }
          )}</p>
          <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
          <button class="delete-btn" data-id="${order.order_id}">🗑 Delete</button>
        </div>
      `
        )
        .join("");

      // ✅ Attach delete events
      document.querySelectorAll(".delete-btn").forEach((btn) => {
        btn.addEventListener("click", async () => {
          const orderId = btn.dataset.id;
          if (!confirm("Are you sure you want to delete this order?")) return;

          try {
            const res = await fetch("assets/resources/helpers/delete_order.php", {
              method: "POST",
              headers: { "Content-Type": "application/x-www-form-urlencoded" },
              body: `order_id=${orderId}`,
            });
            const result = await res.json();
            if (result.success) {
              btn.closest(".order-card").remove();
            } else {
              alert(result.message);
            }
          } catch (err) {
            console.error("Error deleting order:", err);
          }
        });
      });
    } catch (err) {
      ordersList.innerHTML = `<p class="error-text">⚠️ Failed to load orders.</p>`;
      console.error(err);
    }
  }

  // ✅ Open modal and fetch orders
  document.querySelector('[data-modal="ordersModal"]').addEventListener("click", (e) => {
    e.preventDefault();
    ordersModal.style.display = "flex";
    fetchOrders();
  });

  // ✅ Close modal
  ordersModal.querySelector(".close-btn").addEventListener("click", () => {
    ordersModal.style.display = "none";
  });

  // ✅ Close when clicking outside modal
  ordersModal.addEventListener("click", (e) => {
    if (e.target.classList.contains("dropdown-modal")) {
      ordersModal.style.display = "none";
    }
  });


});
