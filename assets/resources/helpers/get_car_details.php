<?php
require_once __DIR__ . '/../../../config/config.php';
header('Content-Type: application/json; charset=utf-8');

$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;

if ($car_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid car ID']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM cars WHERE car_id = :car_id LIMIT 1");
    $stmt->execute([':car_id' => $car_id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($car) {
        echo json_encode(['success' => true, 'car' => $car]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Car not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
