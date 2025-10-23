<?php
// config/config.php

$host = "localhost";   // XAMPP default
$db   = "car_ecommerce"; // change to your database name
$user = "root";        // default XAMPP user
$pass = "";            // default has no password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>