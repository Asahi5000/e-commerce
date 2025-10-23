<?php
// authentication
require '../../authenticator.php';
// Database connection
require __DIR__ . "/../../../config/config.php";

// Get user info from session
include __DIR__ . '/../resources/helpers/P.get-filters.php';
// Build base query
include __DIR__ . '/../resources/helpers/P.build-query.php';
// Search filter
include __DIR__ . '/../resources/helpers/P.search-filter.php';
// Category filter
include __DIR__ . '/../resources/helpers/P.category-filter.php';
// Transmission filter
include __DIR__ . '/../resources/helpers/P.transmission-filter.php';
// Order arrangement
include __DIR__ . '/../resources/helpers/P.order-arrangement.php';
// Execute query
include __DIR__ . '/../resources/helpers/P.exe-query.php';
// Fetch categories for filter dropdown
include __DIR__ . '/../resources/helpers/P.fetch-categories.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Products</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/product-styles3.css">
  <link rel="stylesheet" href="../css/status-modal.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="icon" href="../library/images/logo.png" />
</head>
<body>

  <!-- Sidebar -->
    <?php include '../resources/sidebars/products-sidebar.php'; ?>

    <!-- Main Content -->
 
    <div class="main-content">
        <header>
            <button class="hamburger" onclick="toggleSidebar()">☰</button>
            <div class="header-text">
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Here are your product list!</p>
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
        
            <!-- products content can go here -->
        <div class="container mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Car Inventory</h2>
                <!-- Add New Car Button -->
                <button class="add-car-btn" id="openModalBtn">+ Add New Car</button>
            </div>

    
            <?php include '../resources/modal/add-cars-modal.php'; ?>
            <?php include '../resources/modal/add-btn-status.php'; ?>

            <!-- Search & Filter -->
            <form method="get" class="search-filter">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search cars...">

                <select name="category">
                    <option value="">Filter by Category</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" 
                        <?= $category == $cat['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <select name="transmission">
                    <option value="">Filter by Transmission</option>
                    <option value="automatic" <?= $transmission === "automatic" ? "selected" : "" ?>>Automatic</option>
                    <option value="manual" <?= $transmission === "manual" ? "selected" : "" ?>>Manual</option>
                </select>

                <button type="submit" class="btn btn-primary">Apply</button>
            </form>


            <div class="board shadow-lg p-3">
                <div class="table-wrapper">
                    <div style="overflow-x:auto;">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Model Year</th>
                                    <th>Category</th>
                                    <th>Price (₱)</th>
                                    <th>Mileage</th>
                                    <th>Transmission</th>
                                    <th>Fuel</th>
                                    <th>Stock</th>
                                    <th>Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($cars)): ?>
                                <?php foreach ($cars as $car): ?>
                                <tr>
                                    <td data-label="Image">
                                        <?php if (!empty($car['image'])): ?>
                                        <img src="../../<?= htmlspecialchars($car['image']); ?>" 
                                        alt="Car Image" class="car-thumb">
                                        <?php else: ?>
                                        <img src="../../assets/uploads/car/default.jpg" 
                                        alt="Default Car" class="car-thumb">
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Name"><?= htmlspecialchars($car['name']); ?></td>
                                    <td data-label="Brand"><?= htmlspecialchars($car['brand']); ?></td>
                                    <td data-label="Model Year"><?= htmlspecialchars($car['model_year']); ?></td>
                                    <td data-label="Category"><?= htmlspecialchars($car['category_name'] ?? 'Uncategorized'); ?></td>
                                    <td data-label="Price">₱<?= number_format($car['price'], 2); ?></td>
                                    <td data-label="Mileage"><?= $car['mileage'] ? number_format($car['mileage']) . ' km' : '-'; ?></td>
                                    <td data-label="Transmission"><?= ucfirst($car['transmission']); ?></td>
                                    <td data-label="Fuel"><?= ucfirst($car['fuel_type']); ?></td>
                                    <td data-label="Stock"><?= $car['stock']; ?></td>
                                    <td data-label="Added"><?= date("Y-m-d", strtotime($car['created_at'])); ?></td>
<td data-label="Actions">
    <button 
        class="btn btn-warning editBtn"
        data-id="<?= $car['car_id']; ?>"
        data-name="<?= htmlspecialchars($car['name']); ?>"
        data-brand="<?= htmlspecialchars($car['brand']); ?>"
        data-model_year="<?= htmlspecialchars($car['model_year']); ?>"
        data-category_id="<?= $car['category_id']; ?>"
        data-price="<?= $car['price']; ?>"
        data-mileage="<?= $car['mileage']; ?>"
        data-transmission="<?= $car['transmission']; ?>"
        data-fuel_type="<?= $car['fuel_type']; ?>"
        data-stock="<?= $car['stock']; ?>"
        data-description="<?= htmlspecialchars($car['description']); ?>"
    >
    <i class='bx bx-edit'></i></i> Edit
    </button>
<button 
    type="button" 
    class="btn btn-danger deleteBtn"
    data-id="<?= $car['car_id']; ?>"
    data-name="<?= htmlspecialchars($car['name']); ?>">
    <i class='bx bx-trash'></i> Delete
</button>

</td>
<!-- Shared Overlay -->
<div id="edit-delete-overlay" class="edit-delete-overlay"></div>
<?php include '../resources/modal/edit-car-modal.php'; ?>
<?php include '../resources/modal/delete-car-modal.php'; ?>

                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="12" class="text-center text-muted">No cars found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


  <!-- Script -->
<!-- Include the add car button script -->
<script src="../script/add-car-btn.js"></script>
<!-- Include the product status modal script -->
<script src="../script/product-status-modal.js"></script>
<!-- Include the image preview script -->
<script src="../script/theme-toggle.js"></script>
<!-- Sidebar Toggle Script -->
<script src="../script/sidebar.js"></script>
<!-- User Menu Toggle Script -->
<script src="../script/toggleUserMenu.js"></script>
<!-- Edit and Delete Car Scripts -->
<script src="../script/edit-car.js"></script>
<script src="../script/delete-car.js"></script>

</body>
</html>
