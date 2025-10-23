<?php
require_once __DIR__ . '/../../../config/config.php';
session_name("customer_session");
session_start();

header('Content-Type: application/json');

// ✅ Verify login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_POST['order_id'] ?? null;

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'No order ID provided']);
    exit;
}

try {
    // ✅ Check if order belongs to this user
    $check = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
    $check->execute([$order_id, $user_id]);
    $order = $check->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found or unauthorized']);
        exit;
    }

    // ✅ Delete the order
    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ? AND user_id = ?");
    $stmt->execute([$order_id, $user_id]);

    echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
