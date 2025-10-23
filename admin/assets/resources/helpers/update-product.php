<?php
// admin/assets/resources/helpers/update-product.php
require_once __DIR__ . "/../../../../config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $car_id       = intval($_POST['car_id']);
        $name         = trim($_POST['name']);
        $brand        = trim($_POST['brand']);
        $model_year   = $_POST['model_year'] ?: null;
        $category_id  = $_POST['category_id'] ?: null;
        $price        = $_POST['price'];
        $mileage      = $_POST['mileage'] ?: null;
        $transmission = $_POST['transmission'];
        $fuel_type    = $_POST['fuel_type'];
        $stock        = $_POST['stock'];
        $description  = trim($_POST['description']);
        $imagePath    = null;

        // Upload new image if provided
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . "/../../uploads/car/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileName = time() . "_" . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = "assets/uploads/car/" . $fileName;
            }
        }

        $sql = "UPDATE cars SET 
                    category_id = :category_id, 
                    name = :name, 
                    brand = :brand, 
                    model_year = :model_year, 
                    price = :price, 
                    mileage = :mileage, 
                    transmission = :transmission, 
                    fuel_type = :fuel_type, 
                    description = :description, 
                    stock = :stock";

        if ($imagePath) {
            $sql .= ", image = :image";
        }

        $sql .= " WHERE car_id = :car_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':category_id', $category_id ?: null, $category_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':brand', $brand);
        $stmt->bindValue(':model_year', $model_year ?: null, $model_year ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':mileage', $mileage ?: null, $mileage ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':transmission', $transmission);
        $stmt->bindValue(':fuel_type', $fuel_type);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);

        if ($imagePath) {
            $stmt->bindValue(':image', $imagePath);
        }

        $stmt->bindValue(':car_id', $car_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../../pages/products.php?status=success&message=" . urlencode("Car updated successfully!"));

        exit;

    } catch (Exception $e) {
        echo "Error updating product: " . $e->getMessage();
    }
} else {
    header("Location: ../../pages/products.php");
    exit;
}
