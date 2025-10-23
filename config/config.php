<?php
$host = 'db.fr-pari1.bengt.wasmernet.com';
$port = 10272;
$dbname = 'car_ecommerce';
$username = '9ef7aec87d4a8000ccdb856df051';
$password = '068f9ef7-aec8-7f33-8000-1e0981ccd069';


try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
?>


