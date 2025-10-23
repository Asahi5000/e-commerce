<?php
session_start();

include '../config/config.php'; // adjust if needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usernameOrEmail = trim($_POST['username'] ?? '');
    $password        = trim($_POST['password'] ?? '');

    try {
        $sql = "SELECT user_id, name, username, email, password, role, profile_image
                FROM users 
                WHERE username = :user OR email = :user 
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user', $usernameOrEmail, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // ⚠️ If you hash passwords, replace this with password_verify()
            if ($password === $user['password']) {
                if ($user['role'] === 'admin') {
                    $_SESSION['user_id']        = $user['user_id'];
                    $_SESSION['name']           = $user['name'];
                    $_SESSION['username']       = $user['username'];
                    $_SESSION['email']          = $user['email'];
                    $_SESSION['role']           = $user['role'];
                    $_SESSION['profile_image']  = $user['profile_image'] ?? "default.png";

                    header("Location: assets/pages/dashboard.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Access denied! Only admins can log in.";
                    header("Location: index.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Invalid password.";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "User not found.";
            header("Location: index.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: index.php");
        exit();
    }
}
