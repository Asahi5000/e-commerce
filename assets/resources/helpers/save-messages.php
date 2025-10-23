<?php
require_once __DIR__ . "/../../../config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) 
                                    VALUES (:name, :email, :subject, :message)");
            $stmt->execute([
                ':name'    => $name,
                ':email'   => $email,
                ':subject' => $subject,
                ':message' => $message
            ]);

            header("Location: ../../../index.php?message=success");
            exit;
        } catch (PDOException $e) {
            header("Location: ../../../index.php?message=error");
            exit;
        }
    } else {
        header("Location: ../../../index.php?message=error");
        exit;
    }
}
