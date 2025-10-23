<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Start customer session
session_name("customer_session");
session_start();

require_once __DIR__ . "/../../../config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Fetch user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password']) { // ⚠️ For production, use password_verify()

        // Allow only customers
        if ($user['role'] !== 'customer') {
            $_SESSION['login_error'] = "Access denied! Only customer accounts can log in here.";
            header("Location: ../../../index.php");
            exit;
        }

        // ✅ Store full user info in session
        $_SESSION['user_id']   = $user['user_id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['name']      = $user['name'];
        $_SESSION['email']     = $user['email'];
        $_SESSION['phone']     = $user['phone'];
        $_SESSION['address']   = $user['address'];
        $_SESSION['role']      = $user['role'];
        
        // ✅ ADD THIS LINE → Store profile image in session (fixes avatar not updating)
        $_SESSION['profile_image'] = !empty($user['profile_image']) ? $user['profile_image'] : 'default.jpg';

        // ✅ Redirect to homepage
        header("Location: ../../../index.php");
        exit;

    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: ../../../index.php");
        exit;
    }
}
