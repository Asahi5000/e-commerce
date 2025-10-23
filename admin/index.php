<?php
session_start();
$errorMessage = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Admin Login Page</title>
    <link rel="stylesheet" href="../admin/assets/css/login-styles.css">
    <link rel="stylesheet" href="../admin/assets/css/index-modal.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="../admin/assets/library/images/logo.png" />
</head>

<body>

<div class="container-bg">
        <video autoplay loop muted plays-inline class="bg-video">
            <source src="assets/library/videos/bg-video.mp4" type="video/mp4">
        </video>    
        
    <div class="wrapper">
        <div class="form-box">
            <div class="logo">
                <img src="assets/library/images/logo.png" alt="Logo">
            </div>

            <div class="header-text">
                <h2>Admin Login</h2>
                <p>Login to access your admin panel</p>
            </div>

            <form id="login" class="input-group" action="login-check.php" method="post">
                <div class="input-box">
                    <input type="text" name="username" placeholder="username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" class="btn btn1">Login</button>
            </form>

        </div>
    </div>
</div>

    <!-- Modal -->
    <?php include '../admin/assets/resources/modal/index-modal.php'; ?>

    <!-- Script -->
    <!-- Modal Script -->
<script src="../admin/assets/script/index-modal.js"></script>
<?php if (!empty($errorMessage)): ?>
  <script>
    window.errorMessage = "<?php echo addslashes($errorMessage); ?>";
  </script>
<?php endif; ?>
    <!-- End Modal Script -->

</body>
</html>
