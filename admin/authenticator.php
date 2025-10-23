<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

// ✅ Make session variables available
$username = $_SESSION['username'];
$name     = $_SESSION['name'] ?? '';
$email    = $_SESSION['email'] ?? '';
$role     = $_SESSION['role'];
?>
