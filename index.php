<?php
ob_start(); // Prevents "headers already sent" errors

// ✅ Start session safely before any HTML
if (session_status() === PHP_SESSION_NONE) {
    session_name("customer_session");
    session_start();
    
require_once "config/config.php";
    // Fetch Admin Info 
include "assets/resources/helpers/fetch-admin-info.php";



// Pagination settings
$limit = 6; 
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;


// Default sorting
$sort = $_GET['sort'] ?? 'price_asc';
$orderBy = "cars.price ASC";

if ($sort === "price_desc") {
    $orderBy = "cars.price DESC";
} elseif ($sort === "year_desc") {
    $orderBy = "cars.model_year DESC";
} elseif ($sort === "year_asc") {
    $orderBy = "cars.model_year ASC";
}

// Filters
$brand = $_GET['brand'] ?? '';
$transmission = $_GET['transmission'] ?? '';
$fuel = $_GET['fuel'] ?? '';

$where = [];
$params = [];

if (!empty($brand)) {
    $where[] = "cars.brand = :brand";
    $params[':brand'] = $brand;
}
if (!empty($transmission)) {
    $where[] = "cars.transmission = :transmission";
    $params[':transmission'] = $transmission;
}
if (!empty($fuel)) {
    $where[] = "cars.fuel_type = :fuel";
    $params[':fuel'] = $fuel;
}

$whereSQL = "";
if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

// Fetch total cars count (with filters)
$totalSql = "SELECT COUNT(*) 
             FROM cars 
             LEFT JOIN car_categories ON cars.category_id = car_categories.category_id
             $whereSQL";
$stmt = $conn->prepare($totalSql);
$stmt->execute($params);
$totalCars = $stmt->fetchColumn();
$totalPages = ceil($totalCars / $limit);

// Fetch cars (with filters, sorting, pagination, category)
$sql = "SELECT cars.*, car_categories.category_name AS category
        FROM cars
        LEFT JOIN car_categories ON cars.category_id = car_categories.category_id
        $whereSQL
        ORDER BY $orderBy
        LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// For filter dropdowns
$brands = $conn->query("SELECT DISTINCT brand FROM cars ORDER BY brand")->fetchAll(PDO::FETCH_COLUMN);
$transmissions = $conn->query("SELECT DISTINCT transmission FROM cars ORDER BY transmission")->fetchAll(PDO::FETCH_COLUMN);
$fuels = $conn->query("SELECT DISTINCT fuel_type FROM cars ORDER BY fuel_type")->fetchAll(PDO::FETCH_COLUMN);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My E-Commerce</title>
    <link rel="stylesheet" href="assets/css/index-styles.css">
    <link rel="stylesheet" href="assets/css/model-section.css">
    <link rel="stylesheet" href="assets/css/about-section.css">
    <link rel="stylesheet" href="assets/css/contact-section.css">
    <link rel="stylesheet" href="assets/css/msg-modal.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/view-details.css">
    <link rel="stylesheet" href="assets/css/AuthModal.css">
    <link rel="stylesheet" href="assets/css/nav-user.css">
    <link rel="stylesheet" href="assets/css/dropdown-modal2.css">
    <link rel="stylesheet" href="assets/css/purchaseModal.css">
    <link rel="stylesheet" href="assets/css/orders.css">
    <link rel="icon" href="admin/assets/library/images/logo.png" />
</head>

<body>
    <!-- Home Section -->
<section id="home-section">
    <div class="container-bg">
        <!-- Background Video -->
        <video autoplay loop muted plays-inline class="bg-video">
            <source src="videos/lambo-bg.mp4" type="video/mp4">
        </video>

        <header>

<?php session_name("customer_session"); session_start();  ?>
<nav class="navbar">
  <a href="#home-section">
    <img src="images/logo.png" alt="Logo" class="logo">
  </a>

  <div class="hamburger">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <div class="nav-right">
    <ul class="nav-links">
      <li><a href="#home-section">Home</a></li>
      <li><a href="#model-section">Models</a></li>
      <li><a href="#about-section">About Us</a></li>
      <li><a href="#contact-section">Contact</a></li>
    </ul>

    <ul class="nav-auth">
<?php if (isset($_SESSION['user_id'])): ?>
  <li class="nav-user-dropdown">
<a href="javascript:void(0)" class="user-toggle">
  <img 
    src="<?php echo !empty($_SESSION['profile_image']) 
      ? 'admin/assets/uploads/user/' . $_SESSION['profile_image'] . '?v=' . time() 
      : 'admin/assets/uploads/user/default.jpg'; ?>" 
    alt="User" class="user-avatar">
  <span class="user-name"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
</a>



    <ul class="dropdown-menu">
      <li><a href="#" class="open-modal" data-modal="profileModal">👤 My Profile</a></li>
      <li><a href="#" class="open-modal" data-modal="ordersModal">📦 My Orders</a></li>
      <li><a href="#" class="open-modal" data-modal="logoutModal">🚪 Logout</a></li>
    </ul>
  </li>
<?php else: ?>
  <li><a href="#" class="login-btn" onclick="openLoginModal()">Login</a></li>
<?php endif; ?>



    </ul>
  </div>
</nav>

<!-- Overlay placed OUTSIDE nav -->
<div class="nav-overlay"></div>

        </header>
    </div>
</section>


<!-- Car Models Section -->
<section class="model-section" id="model-section">
    <div class="model-container">
        <h2>Our Car Models</h2>

        <!-- Filter & Sort -->
        <form method="GET" class="filter-bar">
            <select name="brand" onchange="this.form.submit()">
                <option value="">All Brands</option>
                <?php foreach ($brands as $b): ?>
                <option value="<?= htmlspecialchars($b) ?>" <?= $brand === $b ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <select name="transmission" onchange="this.form.submit()">
                <option value="">All Transmissions</option>
                <?php foreach ($transmissions as $t): ?>
                <option value="<?= htmlspecialchars($t) ?>" <?= $transmission === $t ? 'selected' : '' ?>>
                    <?= ucfirst($t) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <select name="fuel" onchange="this.form.submit()">
                <option value="">All Fuel Types</option>
                <?php foreach ($fuels as $f): ?>
                <option value="<?= htmlspecialchars($f) ?>" <?= $fuel === $f ? 'selected' : '' ?>>
                    <?= ucfirst($f) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <select name="sort" onchange="this.form.submit()">
                <option value="price_asc" <?= $sort === "price_asc" ? 'selected' : '' ?>>Price: Low → High</option>
                <option value="price_desc" <?= $sort === "price_desc" ? 'selected' : '' ?>>Price: High → Low</option>
                <option value="year_desc" <?= $sort === "year_desc" ? 'selected' : '' ?>>Year: Newest</option>
                <option value="year_asc" <?= $sort === "year_asc" ? 'selected' : '' ?>>Year: Oldest</option>
            </select>
        </form>

        <!-- Car Grid -->
<div class="model-grid grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
    <?php if (!empty($cars)) { 
        foreach ($cars as $car) { 
            $imagePath = !empty($car['image']) 
                        ? "admin/" . $car['image']
                        : "assets/uploads/car/default.png";
    ?>
    <div class="car-card border rounded-lg overflow-hidden shadow hover:shadow-lg transition relative">
        <div class="car-image">
            <img src="<?= htmlspecialchars($imagePath) ?>" 
                 alt="<?= htmlspecialchars($car['name']) ?>" 
                 class="w-full h-48 object-cover">
        </div>
        <div class="car-details p-4">
            <h3 class="text-lg font-semibold"><?= htmlspecialchars($car['name']) ?></h3>
            <p class="text-gray-500"><?= htmlspecialchars($car['brand']) ?> • <?= htmlspecialchars($car['model_year']) ?></p>
            <p class="car-price text-red-600 font-bold mt-2">₱<?= number_format($car['price'], 2) ?></p>

<button 
    class="view-btn"
    onclick="openModal(
        '<?= htmlspecialchars($car['name'], ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['brand'], ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['model_year'], ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['price'], ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['image'] ? 'admin/' . $car['image'] : 'assets/uploads/car/default.png', ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['description'] ?? 'No description available', ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['category'] ?? 'N/A', ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['mileage'] ?? 0, ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['transmission'] ?? 'Unknown', ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['fuel_type'] ?? 'Unknown', ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['stock'] ?? 0, ENT_QUOTES) ?>',
        '<?= htmlspecialchars($car['car_id'], ENT_QUOTES) ?>'
    )">
    View Details
</button>



        </div>
    </div>
    <?php } } else { ?>
        <p class="col-span-full text-center text-gray-500">No cars found with the selected filters.</p>
    <?php } ?>
</div>

        <!-- Pagination -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>&brand=<?= urlencode($brand) ?>&transmission=<?= urlencode($transmission) ?>&fuel=<?= urlencode($fuel) ?>&sort=<?= urlencode($sort) ?>" 
                class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</section>


    <!-- About Section --> 
<section class="about-section" id="about-section"> 
    <div class="about-container"> 
        <!-- Left side About Us --> 
        <aside> 
            <h1>About Us</h1>
            <p>At Crimson Grand Drive, we redefine luxury car ownership 
               with a seamless online experience built on trust, elegance, 
               and innovation. From timeless classics to modern masterpieces, 
               we connect discerning drivers to the world’s finest automobiles—making 
               every journey a statement of prestige.

            </p>
        </aside> 

        <!-- Timeline --> 
        <div class="timeline"> 
            <div class="timeline-line"></div> 
            <div class="timeline-dot active" data-step="1"></div> 
            <div class="timeline-dot" data-step="2"></div> 
            <div class="timeline-dot" data-step="3"></div> 
            <div class="timeline-dot" data-step="4"></div> 
        </div>

        <!-- Content --> 
        <div class="about-content">  
            <h3 id="milestone-title">Our Beginning</h3> 
            <p id="milestone-text"> 
                At Crimson Grand Drive, our journey began with a passion 
                for redefining luxury on the road—bringing the world’s 
                finest cars closer to enthusiasts who seek not just vehicles, 
                but experiences of elegance, power, and prestige.
 
            </p> 
            <div class="about-image"> 
                <img id="milestone-image" src="images/about1.jpg" alt="Milestone"> 
            </div> 
        </div>
    </div> 
</section>
    
<!-- Contact Section -->
<section class="contact-section" id="contact-section">
            <!-- Background Video -->
        <video autoplay loop muted plays-inline class="contact-bg">
            <source src="videos/white-bg.mp4" type="video/mp4">
        </video>

    <div class="contact-container">

        <!-- Left: Contact Form -->
        <div class="contact-form">
            <h2>Contact</h2>
            <p>Get in touch with us</p>
            <form action="assets/resources/helpers/save-messages.php" method="POST">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="subject" placeholder="Subject">
                <textarea name="message" placeholder="Message" rows="4" required></textarea>
                <button type="submit">Send</button>
            </form>
        </div>

        <!-- Right: Admin Contact Info -->
        <div class="contact-info">
            <ul>
                <li><span class="icon">📞</span> <?= htmlspecialchars($admin['phone']) ?></li>
                <li><span class="icon">✉️</span> <?= htmlspecialchars($admin['email']) ?></li>
                <li><span class="icon">📍</span> <?= htmlspecialchars($admin['address']) ?></li>
            </ul>

            <?php
            // Set correct folder path for profile images
            $imagePath = "admin/assets/uploads/user/";

            // Check if admin has profile image, otherwise use default.png
            $profileImage = (!empty($admin['profile_image']) && file_exists($imagePath . $admin['profile_image']))
            ? $admin['profile_image']: "default.png";
            ?>

            <div class="contact-image">
                <img src="<?= $imagePath . htmlspecialchars($profileImage) ?>" 
                alt="<?= htmlspecialchars($admin['name']) ?>">
            </div>
        </div>

    </div>
</section>


<footer class="footer">
    <div class="footer-container">
    
    <!-- Logo & Motto -->
        <div class="footer-section logo-section">
            <img src="images/logo.png" alt="Lamborghini Logo" class="footer-logo">
            <p class="motto">“Your Gateway to Modern Luxury Cars.”</p>
        </div>

        <!-- Quick Links -->
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#home-section">Home</a></li>
                <li><a href="#model-section">Models</a></li>
                <li><a href="#about-section">About Us</a></li>
                <li><a href="#contact-section">Contact</a></li>
                <li><a href="#" class="login-btn" onclick="openLoginModal()">Login</a></li>
            </ul>
        </div>

        <!-- About -->
        <div class="footer-section">
            <h3>About</h3>
            <p>
                Crimson Grand Drive delivers a seamless path to luxury 
                car ownership—where elegance, trust, and innovation 
                drive every journey.

            </p>
        </div>

        <!-- Contact -->
        <div class="footer-section">
            <h3>Contact</h3>
            <?php if ($admin): ?>
                <p>Email: <?= htmlspecialchars($admin['email']) ?></p>
                <p>Phone: <?= htmlspecialchars($admin['phone']) ?></p>
                <p>Address: <?= htmlspecialchars($admin['address']) ?></p>
            <?php else: ?>
                <p>Email: not available</p>
                <p>Phone: not available</p>
                <p>Address: not available</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 Crimson Grand Drive. All Rights Reserved.</p>
    </div>
</footer>


<?php include 'assets/resources/modal/profileModal.php'; ?>
<?php include 'assets/resources/modal/orderModal.php'; ?>
<?php include 'assets/resources/modal/logoutModal.php'; ?>

<?php include 'assets/resources/modal/success-msg.php'; ?>
<?php include 'assets/resources/modal/error-msg.php'; ?>
<?php include 'assets/resources/modal/view-details.php'; ?>
<?php include 'assets/resources/modal/purchaseModal.php'; ?>
<?php include 'assets/resources/modal/loginModal.php'; ?>
<?php include 'assets/resources/modal/registerModal.php'; ?>


<?php if (isset($_SESSION['show_login_modal']) && $_SESSION['show_login_modal']): ?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    openLoginModal();
  });
</script>
<?php 
  unset($_SESSION['show_login_modal']); 
endif; ?>


<!-- Script -->

<script src="assets/script/dropdown-modal.js"></script>
<script src="assets/script/profile-modal.js?v=2"></script>


<script src="assets/script/loginModalFn.js"></script>
<script src="assets/script/nav-user.js"></script>
<script src="assets/script/hamburgerFn.js"></script>
<script src="assets/script/loginModalError.js"></script>

<script src="assets/script/milestone.js"></script>
<script src="assets/script/msg-modal.js"></script>
<script src="assets/script/view-details.js"></script>

<script src="assets/script/profile_update.js"></script>
<script src="assets/script/purchase.js"></script>

<script src="assets/script/orders.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  // Handle all dropdown "open-modal" links
  document.querySelectorAll(".open-modal").forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const modalId = link.getAttribute("data-modal");
      const modal = document.getElementById(modalId);
      if (modal) {
        if (modalId === "ordersModal") {
          // Special case: fetch orders dynamically
          if (typeof window.openOrdersModal === "function") {
            window.openOrdersModal();
          }
        } else {
          // Generic modal open
          modal.style.display = "flex";
          modal.setAttribute("aria-hidden", "false");
        }
      }
    });
  });

  // Universal close buttons for all modals
  document.querySelectorAll(".dropdown-modal .close-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      btn.closest(".dropdown-modal").style.display = "none";
    });
  });

  // Close modals when clicking outside content
  document.querySelectorAll(".dropdown-modal").forEach((modal) => {
    modal.addEventListener("click", (e) => {
      if (e.target.classList.contains("dropdown-modal")) {
        modal.style.display = "none";
      }
    });
  });
});
</script>


</body>
</html>
