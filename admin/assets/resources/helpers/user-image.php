<?php
function getUserImagePath(): string {
    $filename = $_SESSION['profile_image'] ?? "default.png";
    $relativePath = "../../assets/uploads/user/" . $filename;

    // Check if file exists, otherwise fallback
    if (!file_exists(__DIR__ . "/../../assets/uploads/user/" . $filename) || empty($filename)) {
        return "../../assets/uploads/user/default.png";
    }
    return $relativePath;
}
?>
