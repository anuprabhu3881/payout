<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $parentId = $_POST['parent_id'];

    // Determine level
    $stmt = $pdo->prepare("SELECT level FROM users WHERE id = ?");
    $stmt->execute([$parentId]);
    $parent = $stmt->fetch();
    $level = $parent ? $parent['level'] + 1 : 1;

    $stmt = $pdo->prepare("INSERT INTO users (username, parent_id, level) VALUES (?, ?, ?)");
    $stmt->execute([$username, $parentId, $level]);

    echo "User added successfully";
}
?>
