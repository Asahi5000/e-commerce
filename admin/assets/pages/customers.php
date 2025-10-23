<?php
require '../../authenticator.php';
require '../../../config/config.php';


// Fetch customers (role = 'customer')
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE role = 'customer' ORDER BY created_at DESC");
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Customers</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/customer.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="icon" href="../library/images/logo.png" />


</head>
<body>

  <!-- Sidebar -->
    <?php include '../resources/sidebars/customers-sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <button class="hamburger" onclick="toggleSidebar()">☰</button>
            <div class="header-text">
                <h1>Welcome back, <?php echo $username; ?>!</h1>
                <p>Here are your customer list!</p>
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
<!-- Customer Cards -->
<section class="customer-container">
    <?php if (!empty($customers)): ?>
        <?php foreach ($customers as $customer): ?>
            <?php
                // Check if profile image exists and is not empty
                $profileImage = !empty($customer['profile_image']) && file_exists("../../assets/uploads/user/" . $customer['profile_image'])
                    ? "../../assets/uploads/user/" . htmlspecialchars($customer['profile_image'])
                    : "../../assets/uploads/user/default.jpg";
            ?>
            <div class="customer-card">
                <img src="<?php echo $profileImage; ?>" alt="Profile">
                <h3><?php echo htmlspecialchars($customer['name']); ?></h3>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($customer['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></p>

                <?php if (!empty($customer['phone'])): ?>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($customer['phone']); ?></p>
                <?php endif; ?>

                <?php if (!empty($customer['address'])): ?>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($customer['address']); ?></p>
                <?php endif; ?>

                <small>Joined on: <?php echo date("F j, Y", strtotime($customer['created_at'])); ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-customers">No customers found.</p>
    <?php endif; ?>
</section>

    </div>
  </div>
  

  <!-- Scripts -->
  <script src="../script/theme-toggle.js"></script>
  <script src="../script/sidebar.js"></script>
  <script src="../script/toggleUserMenu.js"></script>
</body>
</html>
