<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$title = trim($_POST['title']);
$content = trim($_POST['content']);
$imagePath = null;

// Обробка зображення
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $imagePath = $targetFile;
    }
}

// Збереження поста в БД
$stmt = $pdo->prepare("INSERT INTO posts (title, content, image, author_id, created_at, likes) VALUES (?, ?, ?, ?, NOW(), 0)");
$stmt->execute([$title, $content, $imagePath, $_SESSION['user_id']]);

header('Location: index.php');
exit;
