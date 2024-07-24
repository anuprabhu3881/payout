<?php
require 'db.php';

$usersStmt = $pdo->query("SELECT * FROM users");
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

$salesStmt = $pdo->query("SELECT * FROM sales");
$sales = $salesStmt->fetchAll(PDO::FETCH_ASSOC);

$payoutsStmt = $pdo->query("SELECT * FROM payouts");
$payouts = $payoutsStmt->fetchAll(PDO::FETCH_ASSOC);

$response = [
    'users' => $users,
    'sales' => $sales,
    'payouts' => $payouts
];

echo json_encode($response);
?>
