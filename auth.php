<?php
session_start();
require 'db.php';

$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    header('Location: index.php');
    exit;
} else {
    $_SESSION['error'] = 'Невірні дані для входу';
    header('Location: login.php');
    exit;
}
