<?php
require_once __DIR__ . '/../../../../config/config.php';
header('Content-Type: application/json');

try {
    // Get total daily sales only for delivered orders
    $stmt = $conn->prepare("
        SELECT 
            DATE(created_at) AS day,
            SUM(total_amount) AS total_sales
        FROM orders
        WHERE status = 'delivered'
        GROUP BY day
        ORDER BY day ASC
    ");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'sales' => $data]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
