<?php
session_start();
include '../../../config/config.php'; // go up 3 levels to reach config.php

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$userId = $_SESSION['user_id'];
$removePhoto = $_POST['remove_photo'] ?? "0";

// Default image filename
$defaultImage = "default.jpg";

try {
    if ($removePhoto === "1") {
        // User chose to remove image
        $sql = "UPDATE users SET profile_image = :defaultImage WHERE user_id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':defaultImage', $defaultImage, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['profile_image'] = $defaultImage;
    } elseif (!empty($_FILES['profile_image']['name'])) {
        // Handle upload if a new file is provided
        $targetDir = "../../uploads/user/"; // relative to helpers/
        $filename = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            $sql = "UPDATE users SET profile_image = :filename WHERE user_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':filename', $filename, PDO::PARAM_STR);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['profile_image'] = $filename;
        }
    }

    header("Location: ../../pages/settings.php?success=settings_saved");
    exit();

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
