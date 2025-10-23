<?php
require_once __DIR__ . '/../../../config/config.php';
session_name("customer_session");
session_start();
header('Content-Type: application/json; charset=utf-8');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => '⚠️ User not logged in.']);
    exit;
}

$required = ['cardHolder', 'cardNumber', 'expiryDate', 'cvv', 'car_id'];
foreach ($required as $f) {
    if (empty($_POST[$f])) {
        echo json_encode(['success' => false, 'message' => "⚠️ Missing field: $f"]);
        exit;
    }
}

$cardHolder = trim($_POST['cardHolder']);
$cardNumber = preg_replace('/\s+/', '', $_POST['cardNumber']);
$expiryDate = trim($_POST['expiryDate']);
$cvv        = trim($_POST['cvv']);
$car_id     = intval($_POST['car_id']);

try {
    // ✅ STEP 1: Check if the user has complete profile info
    $stmt = $conn->prepare("SELECT phone, address FROM users WHERE user_id = :uid");
    $stmt->execute([':uid' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User not found.');
    }

    $missing = [];
    if (empty($user['phone'])) $missing[] = 'Phone number';
    if (empty($user['address'])) $missing[] = 'Address';

    if (!empty($missing)) {
        echo json_encode([
            'success' => false,
            'message' => '⚠️ Please complete your profile before purchasing. Missing: ' . implode(', ', $missing)
        ]);
        exit;
    }

    // ✅ STEP 2: Begin transaction
    $conn->beginTransaction();

    // Fetch car info
    $stmt = $conn->prepare("SELECT price, stock FROM cars WHERE car_id = :car_id FOR UPDATE");
    $stmt->execute([':car_id' => $car_id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$car) throw new Exception('Car not found.');
    if ((int)$car['stock'] <= 0) throw new Exception('This car is out of stock.');

    $carPrice = (float)$car['price'];

    // Validate card
    $stmt = $conn->prepare("
        SELECT card_id, balance
        FROM bank_cards
        WHERE TRIM(card_holder) = :holder
          AND REPLACE(card_number, ' ', '') = :number
          AND expiry_date = :expiry
          AND cvv = :cvv
        LIMIT 1
        FOR UPDATE
    ");
    $stmt->execute([
        ':holder' => $cardHolder,
        ':number' => $cardNumber,
        ':expiry' => $expiryDate,
        ':cvv'    => $cvv
    ]);
    $card = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$card) throw new Exception('Invalid card details.');

    $balance = (float)$card['balance'];
    if ($balance < $carPrice) throw new Exception('Insufficient balance.');

    // Deduct balance
    $newBalance = round($balance - $carPrice, 2);
    $update = $conn->prepare("UPDATE bank_cards SET balance = :bal WHERE card_id = :cid");
    $update->execute([':bal' => $newBalance, ':cid' => $card['card_id']]);

    // Decrease stock
    $stockUpdate = $conn->prepare("UPDATE cars SET stock = stock - 1 WHERE car_id = :cid AND stock > 0");
    $stockUpdate->execute([':cid' => $car_id]);
    if ($stockUpdate->rowCount() === 0) throw new Exception('This car is out of stock.');

    // Insert order
    $order = $conn->prepare("
        INSERT INTO orders (user_id, car_id, total_amount, status, created_at)
        VALUES (:uid, :car_id, :amt, 'confirmed', NOW())
    ");
    $order->execute([':uid' => $user_id, ':car_id' => $car_id, ':amt' => $carPrice]);

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => '✅ Payment successful! Remaining balance: ₱' . number_format($newBalance, 2),
        'remaining_balance' => number_format($newBalance, 2)
    ]);
} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    error_log('Purchase error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Transaction: ' . $e->getMessage()]);
}
?>
