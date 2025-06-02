<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if ($name && $email && $message) {
        $stmt = $pdo->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
        echo "<p style='font-family: sans-serif; padding: 40px; text-align: center; color: green;'>✅ Повідомлення збережено. Дякуємо!</p>";
    } else {
        echo "<p style='font-family: sans-serif; padding: 40px; text-align: center; color: red;'>❌ Будь ласка, заповніть усі поля.</p>";
    }
} else {
    header("Location: index.php");
    exit;
}
