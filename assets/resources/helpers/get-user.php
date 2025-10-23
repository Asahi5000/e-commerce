<?php
// assets/resources/helpers/get-user.php
session_name("customer_session");
session_start();
require_once __DIR__ . "/../../../../config/config.php";

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, name, email, phone, address, profile_image FROM users WHERE user_id = :id LIMIT 1");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

// Ensure default image if empty
if (empty($user['profile_image'])) {
    $user['profile_image'] = 'default.jpg';
}

echo json_encode($user);
