<?php
$host = 'db.fr-par1.bengt.wasmernet.com';
$port = 10272;
$dbname = 'car_ecommerce';
$username = '9ef7aec87d4a8000ccdb856df051';
$password = '068f9ef7-aec8-7f33-8000-1e0981ccd069';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Database connection successful!";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
?>
