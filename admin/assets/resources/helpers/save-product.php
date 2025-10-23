<?php
// admin/assets/resources/helpers/save-product.php

require_once __DIR__ . "/../../../../config/config.php"; // connect DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect inputs
        $name         = trim($_POST['name'] ?? '');
        $brand        = trim($_POST['brand'] ?? '');
        $model_year   = !empty($_POST['model_year']) ? intval($_POST['model_year']) : null;
        $category_id  = !empty($_POST['category_id']) ? intval($_POST['category_id']) : null;
        $price        = !empty($_POST['price']) ? floatval($_POST['price']) : null;
        $mileage      = !empty($_POST['mileage']) ? intval($_POST['mileage']) : null;
        $transmission = $_POST['transmission'] ?? '';
        $fuel_type    = $_POST['fuel_type'] ?? '';
        $stock        = !empty($_POST['stock']) ? intval($_POST['stock']) : 1;
        $description  = trim($_POST['description'] ?? '');
        $imagePath    = null;

        // ===== Validate required fields =====
        if (empty($name) || empty($price) || empty($transmission) || empty($category_id)) {
            throw new Exception("Name, price, transmission, and category are required.");
        }

        // ===== Handle Image Upload =====
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . "/../../uploads/car/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . "_" . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                // ✅ Store relative path (for products.php)
                $imagePath = "assets/uploads/car/" . $fileName;
            } else {
                throw new Exception("Image upload failed.");
            }
        }

        // ===== Insert into database =====
        $sql = "INSERT INTO cars 
            (category_id, name, brand, model_year, price, mileage, transmission, fuel_type, description, image, stock) 
            VALUES 
            (:category_id, :name, :brand, :model_year, :price, :mileage, :transmission, :fuel_type, :description, :image, :stock)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':brand', $brand);
        $stmt->bindValue(':model_year', $model_year !== null ? $model_year : null, $model_year !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':mileage', $mileage !== null ? $mileage : null, $mileage !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':transmission', $transmission);
        $stmt->bindValue(':fuel_type', $fuel_type);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':image', $imagePath);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);

        $stmt->execute();

        // ✅ Redirect with success
        header("Location: ../../pages/products.php?status=success&message=" . urlencode("Car added successfully!"));
        exit;

    } catch (Exception $e) {
        // ✅ Redirect with error
        header("Location: ../../pages/products.php?status=error&message=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: ../../pages/products.php");
    exit;
}
