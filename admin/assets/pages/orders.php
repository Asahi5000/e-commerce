<?php
require '../../authenticator.php';
require __DIR__ . "/../../../config/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Orders</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/order2.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="icon" href="../library/images/logo.png" />
</head>
<body>

  <!-- Sidebar -->
    <?php include '../resources/sidebars/orders-sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <button class="hamburger" onclick="toggleSidebar()">☰</button>
            <div class="header-text">
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Here are your order list!</p>
            </div>

            <?php
            // Example: fetch user image from DB later, fallback to default
            $userImage = "../../images/user.png"; // <- current path
            if (!file_exists(__DIR__ . "/../../assets/uploads/user/user.png") || empty($userImage)) {
                $userImage = "../../assets/uploads/user/default-image.jpg";
            }
            ?>

            <!-- Theme Toggle Button -->
            <button class="theme-toggle" onclick="toggleTheme()">🌙</button>

            <?php
                $profileImage = "../../assets/uploads/user/" . ($_SESSION['profile_image'] ?? "default.png");
                $username = $_SESSION['username'] ?? "Guest";
                $email = $_SESSION['email'] ?? "";
            ?>
            <!-- User Info and Dropdown Menu -->
            <div class="user-info" onclick="toggleUserMenu()">
                <img src="<?php echo $profileImage; ?>" alt="User">
                <span><?php echo htmlspecialchars($username); ?></span>
            </div>

            <div class="user-menu" id="userMenu">
                <div class="profile-info">
                    <strong><?php echo $name; ?></strong>
                    <small><?php echo $email; ?></small>
                </div>
                <hr>
                <a href="../pages/settings.php"><i class='bx bxs-user'></i> Profile</a>
                <a href="../../logout.php"><i class='bx bx-log-out' ></i> Logout</a>
            </div>

        </header>

    <h2>📋 Order List</h2>    
    <div class="orders-container">
      
      <?php
      try {
          $stmt = $conn->query("
              SELECT o.order_id, o.user_id, o.total_amount, o.status, o.created_at,
                     u.name AS user_name, u.email,
                     c.name AS car_name, c.brand, c.model_year
              FROM orders o
              JOIN users u ON o.user_id = u.user_id
              JOIN cars c ON o.car_id = c.car_id
              ORDER BY o.created_at DESC
          ");
          $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if ($orders) {
              echo "<table>
                      <thead>
                        <tr>
                          <th>Order ID</th>
                          <th>Customer</th>
                          <th>Email</th>
                          <th>Car</th>
                          <th>Amount</th>
                          <th>Status</th>
                          <th>Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>";

              foreach ($orders as $order) {
                  echo "<tr>
                          <td data-label='Order ID'>#{$order['order_id']}</td>
                          <td data-label='Customer'>{$order['user_name']}</td>
                          <td data-label='Email'>{$order['email']}</td>
                          <td data-label='Car'>{$order['brand']} {$order['car_name']} ({$order['model_year']})</td>
                          <td data-label='Amount'>₱" . number_format($order['total_amount'], 2) . "</td>
                          <td data-label='Status'>
                            <span class='status {$order['status']}'>{$order['status']}</span>
                          </td>
                          <td data-label='Date'>" . date('M d, Y', strtotime($order['created_at'])) . "</td>
                          <td data-label='Action'>
                            <button class='action-btn' onclick='openModal({$order['order_id']}, \"{$order['status']}\")'>Update</button>
                          </td>
                        </tr>";
              }

              echo "</tbody></table>";
          } else {
              echo "<p style='text-align:center;color:#b8860b;'>No orders found.</p>";
          }
      } catch (PDOException $e) {
          echo "<p style='color:red;text-align:center;'>Error: " . $e->getMessage() . "</p>";
      }
      ?>
    </div>
  </div>

  <!-- Status Modal -->
  <?php include '../resources/modal/order-status-modal.php'; ?>

  <!-- Toast Notification -->
  <div id="toast"></div>

  <script>
    const modal = document.getElementById('statusModal');
    const modalOrderId = document.getElementById('modalOrderId');
    const modalStatus = document.getElementById('modalStatus');
    const toast = document.getElementById('toast');

    function openModal(orderId, currentStatus) {
      modal.style.display = 'flex';
      modalOrderId.value = orderId;
      modalStatus.value = currentStatus;
    }

    function closeModal() {
      modal.style.display = 'none';
    }

    function showToast(message, isError = false) {
      toast.innerText = message;
      toast.style.background = isError ? '#e74c3c' : '#2ecc71';
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 3000);
    }

    function saveStatus() {
      const orderId = modalOrderId.value;
      const newStatus = modalStatus.value;

      fetch('../resources/helpers/update_order_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `order_id=${orderId}&status=${encodeURIComponent(newStatus)}`
      })
      .then(res => res.json())
      .then(data => {
        showToast(data.message, !data.success);
        if (data.success) setTimeout(() => location.reload(), 1000);
      })
      .catch(() => showToast("Error updating order!", true))
      .finally(() => closeModal());
    }

    window.onclick = function(event) {
      if (event.target === modal) closeModal();
    };
  </script>




  <!-- Script -->
<script src="../script/theme-toggle.js"></script>
<script src="../script/sidebar.js"></script>

<script src="../script/toggleUserMenu.js"></script>

</body>
</html>
