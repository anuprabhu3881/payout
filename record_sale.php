<?php
require 'db.php';

function calculatePayouts($pdo, $userId, $saleId, $amount, $level) {
    $percentages = [10, 5, 3, 2, 1];
    $stmt = $pdo->prepare("SELECT parent_id FROM users WHERE id = ?");
    for ($i = 0; $i < 5; $i++) {
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        if (!$user || !$user['parent_id']) break;
        $userId = $user['parent_id'];
        $commission = $amount * $percentages[$i] / 100;
        $stmtPayout = $pdo->prepare("INSERT INTO payouts (user_id, sale_id, commission, level) VALUES (?, ?, ?, ?)");
        $stmtPayout->execute([$userId, $saleId, $commission, $i + 1]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $amount = $_POST['amount'];

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    if ($stmt->rowCount() == 0) {
        echo "User does not exist";
        exit;
    }

    // Record sale
    $stmt = $pdo->prepare("INSERT INTO sales (user_id, amount) VALUES (?, ?)");
    $stmt->execute([$userId, $amount]);
    $saleId = $pdo->lastInsertId();

    // Calculate and record payouts
    calculatePayouts($pdo, $userId, $saleId, $amount, 1);

    echo "Sale recorded and payouts calculated";
}
?>
