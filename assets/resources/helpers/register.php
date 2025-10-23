<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . "/../../../config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    // Validation
    if (empty($name) || empty($username) || empty($email) || empty($password)) {
        $_SESSION['register_error'] = "All fields are required.";
        header("Location: ../../../index.php");
        exit;
    }

    if ($password !== $confirm) {
        $_SESSION['register_error'] = "Passwords do not match.";
        header("Location: ../../../index.php");
        exit;
    }

    try {
        // Check if email already exists
        $check = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
        $check->execute([':email' => $email]);
        if ($check->fetch()) {
            $_SESSION['register_error'] = "Email is already registered.";
            header("Location: ../../../index.php");
            exit;
        }

        // Insert new user (store plain password)
        $stmt = $conn->prepare("
            INSERT INTO users (name, username, email, password, role, created_at) 
            VALUES (:name, :username, :email, :password, 'customer', NOW())
        ");

        $stmt->execute([
            ':name'     => $name,
            ':username' => $username,
            ':email'    => $email,
            ':password' => $password, // no hash
        ]);

        $_SESSION['register_success'] = "Registration successful. Please login.";
        header("Location: ../../../index.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['register_error'] = "Registration failed: " . $e->getMessage();
        header("Location: ../../../index.php");
        exit;
    }
}
