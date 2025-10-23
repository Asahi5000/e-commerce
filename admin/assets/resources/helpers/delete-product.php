<?php
// admin/assets/resources/helpers/delete-product.php
require_once __DIR__ . "/../../../../config/config.php"; // $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;
    if ($car_id <= 0) {
        header("Location: ../../pages/products.php?error=" . urlencode("Invalid car id"));
        exit;
    }

    try {
        // fetch image path
        $stmt = $conn->prepare("SELECT image FROM cars WHERE car_id = :id LIMIT 1");
        $stmt->execute([':id' => $car_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($row['image'])) {
            $imageRel = $row['image']; // e.g. "admin/assets/uploads/car/xxx.jpg" or "assets/uploads/..."
            // full path from project root
            $fullPath = __DIR__ . "/../../../../" . $imageRel; // __DIR__ is helpers folder
            // normalize path
            $fullPath = str_replace(['\\','/'], DIRECTORY_SEPARATOR, $fullPath);

            if (file_exists($fullPath) && is_file($fullPath)) {
                @unlink($fullPath); // suppress errors, best-effort
            }
        }

        // delete DB row
        $del = $conn->prepare("DELETE FROM cars WHERE car_id = :id");
        $del->execute([':id' => $car_id]);

        header("Location: ../../pages/products.php?status=success&message=" . urlencode("Car deleted successfully!"));
        exit;
    } catch (Exception $e) {
        header("Location: ../../pages/products.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: ../../pages/products.php");
    exit;
}
