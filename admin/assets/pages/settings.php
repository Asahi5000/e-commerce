<?php
require '../../authenticator.php';
require '../../../config/config.php'; // PDO $conn
include '../resources/helpers/user-image.php';

$user_id = $_SESSION['user_id'];

// ✅ Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :id LIMIT 1");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$successMessage = $errorMessage = "";

// ✅ Handle form submission
include '../resources/helpers/settings-handle-form.php';

// ✅ Always get latest profile image
$profile_image = $_SESSION['profile_image'] ?? (!empty($user['profile_image']) ? $user['profile_image'] : "default.jpg");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Settings</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/settings-styles.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="icon" href="../library/images/logo.png" />
</head>
<body>

<!-- Sidebar -->
<?php include '../resources/sidebars/settings-sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <header>
      <button class="hamburger" onclick="toggleSidebar()">☰</button>
      <div class="header-text">
        <h1>Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <p>Here are your profile settings!</p>
      </div>

      <?php
      $userImage = "../../assets/uploads/user/" . $profile_image;
      if (!file_exists(__DIR__ . "/../../assets/uploads/user/" . $profile_image)) {
          $userImage = "../../assets/uploads/user/default.jpg";
      }
      ?>

      <!-- Theme Toggle Button -->
      <button class="theme-toggle" onclick="toggleTheme()">🌙</button>

            <?php
                $profileImage = "../../assets/uploads/user/" . ($_SESSION['profile_image'] ?? "default.jpg");
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

    <!-- Profile Settings Form -->
    <section class="settings-section">
        <?php if (!empty($successMessage)): ?>
            <div class="alert success"><?php echo $successMessage; ?></div>
        <?php elseif (!empty($errorMessage)): ?>
            <div class="alert error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="settings-form">
            <!-- Profile Image Upload -->
            <div class="form-group">
                <label for="profile_image">Profile Image</label>
    
                <!-- Preview -->
                <img id="profilePreview" 
                src="../../assets/uploads/user/<?php echo htmlspecialchars($user['profile_image']); ?>" 
                alt="Profile Image">
    
                <!-- File Input -->
                <!-- Styled Button -->
                <label for="profile_image" class="file-btn">Upload Image</label>

                <!-- Hidden Input -->
                <input type="file" id="profile_image" name="profile_image" accept="image/*" hidden>
    
                <!-- ✅ Hidden input MUST be here -->
                <input type="hidden" name="remove_photo" id="remove_photo" value="0">
        
                <!-- Remove photo button -->
                <button type="button" onclick="removePhoto()" class="btn btn-warning">Remove Photo</button>


                <!-- Remove Photo Confirmation Modal -->
                <?php include '../resources/modal/removePhoto-modal.php'; ?>

            </div>

            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>

            <label for="password">New Password (leave blank if unchanged)</label>
            <input type="password" id="password" name="password">

            <button type="submit" class="btn">💾 Save Changes</button>
        </form>
    </section>
</div>

<!-- Script -->
<script src="../script/theme-toggle.js"></script>
<script src="../script/sidebar.js"></script>
<script src="../script/toggleUserMenu.js"></script>
<script src="../script/previewImage.js"></script>
<script src="../script/removePhoto.js"></script>

</body>
</html>
