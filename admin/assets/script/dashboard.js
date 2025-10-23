document.addEventListener("DOMContentLoaded", () => {
  const canvas = document.getElementById("salesChart");
  const ctx = canvas.getContext("2d");
  let salesChart;

  async function fetchSalesData() {
    try {
      const res = await fetch("../resources/helpers/fetch-sales.php");
      if (!res.ok) throw new Error("Network response was not ok");
      const data = await res.json();

      if (!data.success) throw new Error(data.message || "Invalid response");

      const labels = data.sales.map(item => item.day);
      const values = data.sales.map(item => parseFloat(item.total_sales));

      if (salesChart) salesChart.destroy();

      salesChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels,
          datasets: [{
            label: "Delivered Sales (₱)",
            data: values,
            backgroundColor: "#bba31b",
            borderColor: "#a38f18",
            borderWidth: 1,
            borderRadius: 6,
            barThickness: "flex" // auto width
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          resizeDelay: 200, // smoother redraw
          animation: false,
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                color: "#555",
                callback: value => "₱" + value.toLocaleString(),
                font: { size: 13 }
              },
              grid: {
                color: "rgba(0,0,0,0.05)",
                drawBorder: false
              }
            },
            x: {
              ticks: {
                color: "#555",
                autoSkip: true,
                maxTicksLimit: 10,
                font: { size: 12 }
              },
              grid: { display: false }
            }
          },
          plugins: {
            legend: {
              labels: {
                color: "#333",
                font: { size: 13, weight: "bold" },
                padding: 12
              }
            },
            tooltip: {
              backgroundColor: "#2b2b2b",
              titleColor: "#fff",
              bodyColor: "#fff",
              titleFont: { size: 14, weight: "bold" },
              bodyFont: { size: 13 },
              padding: 10,
              borderColor: "#bba31b",
              borderWidth: 1,
              callbacks: {
                label: context => `₱${(context.parsed.y || 0).toLocaleString()}`
              }
            }
          }
        }
      });
    } catch (err) {
      console.error("Sales data fetch error:", err);
    }
  }

  fetchSalesData();

  // 🧠 Recalculate layout after CSS applies
  let resizeTimer;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      if (salesChart) {
        // Force reflow before resize
        void canvas.offsetWidth;
        salesChart.resize();
        salesChart.update("none");
      }
    }, 600); // ✅ delay enough for CSS to apply fully
  });
});
