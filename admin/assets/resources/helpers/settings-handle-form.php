<?php
// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);
    $password = trim($_POST['password']); // optional

    $targetDir = "../../assets/uploads/user/";
    $profile_image = $user['profile_image'] ?? "default.jpg";

    try {
        // --- Handle remove photo flag (from hidden input) ---
        if (isset($_POST['remove_photo']) && $_POST['remove_photo'] == "1") {
            if (!empty($user['profile_image']) && $user['profile_image'] !== "default.jpg" 
                && file_exists($targetDir . $user['profile_image'])) {
                unlink($targetDir . $user['profile_image']);
            }
            $profile_image = "default.jpg";
        }

        // --- Profile image upload ---
        if (!empty($_FILES['profile_image']['name']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $fileName = time() . "_" . basename($_FILES['profile_image']['name']);
            $targetFile = $targetDir . $fileName;

            $allowedTypes = ['image/jpeg','image/png','image/gif'];
            if (in_array($_FILES['profile_image']['type'], $allowedTypes)) {
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
                    if (!empty($user['profile_image']) && $user['profile_image'] !== "default.jpg" 
                        && file_exists($targetDir . $user['profile_image'])) {
                        unlink($targetDir . $user['profile_image']);
                    }
                    $profile_image = $fileName;
                } else {
                    $errorMessage = "Error uploading image.";
                }
            } else {
                $errorMessage = "Invalid image format. Only JPG, PNG, GIF allowed.";
            }
        }

        // --- Update DB ---
        if (empty($password)) {
            $sql = "UPDATE users SET username = :username, name = :name, email = :email,
                    phone = :phone, address = :address, profile_image = :profile_image
                    WHERE user_id = :id";
            $stmt = $conn->prepare($sql);
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET username = :username, name = :name, email = :email,
                    phone = :phone, address = :address, profile_image = :profile_image, password = :password
                    WHERE user_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':password', $hashedPassword);
        }

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':profile_image', $profile_image);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['username']      = $username;
            $_SESSION['name']          = $name;
            $_SESSION['email']         = $email;
            $_SESSION['profile_image'] = $profile_image;
            $_SESSION['success']       = "✅ Profile updated successfully!";
            header("Location: settings.php");
            exit();
        } else {
            $errorMessage = "❌ Failed to update profile.";
        }

    } catch (PDOException $e) {
        $errorMessage = "Database error: " . $e->getMessage();
    }
}
?>