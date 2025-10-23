<?php
require '../../authenticator.php';
include '../../../config/config.php';



// Initialize variables
$total_customers = 0;
$total_cars_count = 0;
$total_client_messages = 0;
$topCategory = 0;
$topCategoryName = "N/A";

try {
    // 🔹 Total customers
    $stmt = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
    $total_customers = (int) $stmt->fetchColumn();

    // 🔹 Total cars (sum of stock)
    $stmt = $conn->query("SELECT SUM(stock) FROM cars");
    $total_cars_count = (int) $stmt->fetchColumn();

    // 🔹 Total messages
    $stmt = $conn->query("SELECT COUNT(*) FROM messages");
    $total_client_messages = (int) $stmt->fetchColumn();

    // 🔹 Top category (by total stock)
    $stmt = $conn->query("
        SELECT c.category_name, SUM(car.stock) AS total_stock
        FROM cars AS car
        INNER JOIN car_categories AS c ON car.category_id = c.category_id
        GROUP BY c.category_id
        ORDER BY total_stock DESC
        LIMIT 1
    ");
    $top = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($top) {
        $topCategoryName = $top['category_name'];
        $topCategory = (int) $top['total_stock'];
    }

} catch (Throwable $e) {
    // Optional: error_log($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
<link rel="stylesheet" href="../css/styles.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../css/sales.css?v=<?php echo time(); ?>">

  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="icon" href="../library/images/logo.png" />
</head>
<body>

  <!-- Sidebar -->
    <?php include '../resources/sidebars/dashboard-sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        
        <header>
            <button class="hamburger" onclick="toggleSidebar()">☰</button>
            <div class="header-text">
                <h1>Welcome back, <?php echo $username; ?>!</h1>
                <p>Here are your dashboard!</p>
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

        <!-- Cards -->
        <div class="cards">
            <div class="card dark">
                <h3>Total Cars</h3>
                <p>Registered Cars</p>
                <h2><?php echo $total_cars_count; ?></h2>
                <small>- Value</small>
            </div>
<div class="card">
    <h3>Total Customers</h3>
    <p>Registered Accounts</p>
    <h2><?php echo number_format($total_customers); ?></h2>
    <small>- Value</small>
</div>
    <div class="card">
        <h3>Total Client Messages</h3>
        <p>Received</p>
        <h2><?php echo number_format($total_client_messages); ?></h2>
        <small>- Value</small>
    </div>

    <div class="card">
        <h3>Top Car Category</h3>
        <p>By total stock</p>
        <h2><?php echo $topCategory; ?></h2>
        <small>-<?php echo $topCategoryName; ?></small>
    </div>

        </div>

        <!-- Bottom Section -->
        <div class="bottom-section">
<!-- Sales Performance -->
<div class="performance">
  <h3>Sales Performance</h3>
  <div class="chart-wrapper">
    <canvas id="salesChart"></canvas>
  </div>
</div>






                </div>
            </div>
        </div>
    </div>

  <!-- Script -->
<script src="../script/theme-toggle.js"></script>
<script src="../script/sidebar.js"></script>
<!-- src="../script/chart.js"> -->

<script src="../script/toggleUserMenu.js"></script>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../script/dashboard.js"></script>




</body>
</html>
