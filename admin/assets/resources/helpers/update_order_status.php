<?php
require __DIR__ . "/../../../../config/config.php"; // ✅ fixed path

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$order_id || !$status) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
        $stmt->execute([':status' => $status, ':order_id' => $order_id]);

        echo json_encode(['success' => true, 'message' => 'Order status updated successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
