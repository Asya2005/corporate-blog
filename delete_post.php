<?php
session_start();
require 'db.php';

$id = $_GET['id'] ?? null;

// Перевірка авторства
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post || $post['author_id'] != $_SESSION['user_id']) {
    die("⛔ Ви не можете видалити цей пост");
}

// Видалення
$delete = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$delete->execute([$id]);

header("Location: index.php");
exit;
