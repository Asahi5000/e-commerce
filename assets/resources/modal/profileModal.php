<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name("customer_session");
    session_start();
}

require_once __DIR__ . "/../../../config/config.php"; // ✅ adjust if needed

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT username, name, email, phone, address, profile_image 
                            FROM users WHERE user_id = :id LIMIT 1");
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $profile_image = !empty($user['profile_image']) ? $user['profile_image'] : 'default.jpg';
?>
<div id="profileModal" class="dropdown-modal" aria-hidden="true">
  <div class="dropdown-modal-content small">
    <button type="button" class="close-btn">&times;</button>
    <h1>👤 My Profile</h1>

    <form id="profileForm" enctype="multipart/form-data">
      <!-- Profile Image -->
      <div class="form-group">
        <label>Profile Image</label>
        <img id="profilePreview" class="profile-preview"
             src="admin/assets/uploads/user/<?php echo htmlspecialchars($profile_image); ?>?v=<?php echo time(); ?>"
             alt="Profile Picture">
        <input type="file" name="profile_image" accept="image/*">
      </div>

      <h4>Username:</h4>
      <input type="text" name="username" placeholder="Username"
             value="<?php echo htmlspecialchars($user['username']); ?>">

      <h4>Full Name:</h4>
      <input type="text" name="name" placeholder="Full Name"
             value="<?php echo htmlspecialchars($user['name']); ?>">

      <h4>Email:</h4>
      <input type="email" name="email" placeholder="Email"
             value="<?php echo htmlspecialchars($user['email']); ?>">

      <h4>Phone:</h4>
      <input type="text" name="phone" placeholder="Phone Number"
             value="<?php echo htmlspecialchars($user['phone']); ?>">

      <h4>Address:</h4>
      <textarea name="address" rows="3" placeholder="Address"><?php echo htmlspecialchars($user['address']); ?></textarea>

      <h4>New Password:</h4>
      <input type="password" name="password" placeholder="New Password">

      <button type="submit">Save Changes</button>
      <div id="profileMessage" class="profile-message"></div>
    </form>
  </div>
</div>
<?php
    } else {
        echo "<p class='error-message'>User not found.</p>";
    }
} else {

}
?>
