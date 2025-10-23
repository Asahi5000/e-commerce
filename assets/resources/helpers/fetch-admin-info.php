<?php
// Fetch admin info
$sql = "SELECT name, email, phone, address, profile_image 
        FROM users WHERE role = 'admin' LIMIT 1";
$stmt = $conn->query($sql);  // ✅ Use $conn instead of $pdo
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

?>