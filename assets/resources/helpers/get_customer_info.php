<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name("customer_session");
    session_start();
}

require_once __DIR__ . "/../../../config/config.php";
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '⚠️ User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email, phone, address FROM users WHERE user_id = :id LIMIT 1");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode(['success' => true, 'data' => $user]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
