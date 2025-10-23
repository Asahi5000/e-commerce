<?php
session_name("customer_session");
session_start();

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../../config/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

$user_id = $_SESSION['user_id'];
$uploadDir = __DIR__ . '/../../../admin/assets/uploads/user/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

try {
    // Fetch current user image
    $stmtOld = $conn->prepare("SELECT profile_image FROM users WHERE user_id = :id");
    $stmtOld->execute([':id' => $user_id]);
    $oldImg = $stmtOld->fetchColumn();

    $newFileName = $oldImg; // default (if no new upload)

    // 🖼️ Handle uploaded image
    if (!empty($_FILES['profile_image']['name'])) {
        $file = $_FILES['profile_image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid image format']);
            exit;
        }

        $newFileName = 'user_' . uniqid() . '.' . $ext;
        $targetFile = $uploadDir . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
            exit;
        }

        // 🧹 Delete old image if it exists
        if ($oldImg && $oldImg !== 'default.jpg' && file_exists($uploadDir . $oldImg)) {
            unlink($uploadDir . $oldImg);
        }
    }

    // 🧱 Prepare update data
    $fields = [
        'username' => $_POST['username'] ?? '',
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'address' => $_POST['address'] ?? '',
    ];

    $sql = "UPDATE users SET 
                username = :username,
                name = :name,
                email = :email,
                phone = :phone,
                address = :address,
                profile_image = :image";

    $params = [
        ':username' => $fields['username'],
        ':name' => $fields['name'],
        ':email' => $fields['email'],
        ':phone' => $fields['phone'],
        ':address' => $fields['address'],
        ':image' => $newFileName,
        ':id' => $user_id
    ];

    // 🧂 Plain password update
    if (!empty($_POST['password'])) {
        $sql .= ", password = :password";
        $params[':password'] = $_POST['password']; // ⚠️ no hashing
    }

    $sql .= " WHERE user_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $_SESSION['profile_image'] = $newFileName;

    echo json_encode([
        'status' => 'success',
        'message' => 'Profile updated successfully!',
        'profile_image' => $newFileName
    ]);
    exit;

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}
?>
