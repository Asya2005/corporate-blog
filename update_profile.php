<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$avatarName = null;

// Завантаження аватара
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $avatarName = 'avatar_' . $user_id . '.' . $ext;
    move_uploaded_file($_FILES['avatar']['tmp_name'], 'uploads/' . $avatarName);
}

// Оновлення даних
if ($avatarName) {
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, phone=?, avatar=? WHERE id=?");
    $stmt->execute([$name, $email, $phone, $avatarName, $user_id]);
} else {
    $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
    $stmt->execute([$name, $email, $phone, $user_id]);
}

header("Location: profile.php");
exit;
