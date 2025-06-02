<?php
require 'db.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';

function redirectWithError($msg, $name, $email, $phone) {
    $url = 'register.php?error=' . urlencode($msg)
         . '&name=' . urlencode($name)
         . '&email=' . urlencode($email)
         . '&phone=' . urlencode($phone);
    header("Location: $url");
    exit;
}

// Валідація
if (!preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i", $email)) {
    redirectWithError("Email некоректний", $name, $email, $phone);
}

$cleanedPhone = preg_replace("/\s+|\(|\)|\-/", "", $phone);
if (!preg_match("/^\+380\d{9}$/", $cleanedPhone)) {
    redirectWithError("Телефон має бути у форматі +380XXXXXXXXX", $name, $email, $phone);
}

if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
    redirectWithError("Пароль має містити щонайменше 8 символів, літери та цифри", $name, $email, $phone);
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// \c ; \s ;\S ;\d ; \D ; \w ; \W : \xhh ; \0xxx ;  . 


// Збереження
try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $hashedPassword]);
    header("Location: login.php");
} catch (PDOException $e) {
    redirectWithError("Email або телефон вже зайнятий", $name, $email, $phone);
}

$comments = []; // масив коментарів
$stmt = $pdo->prepare("INSERT INTO comments (post_id, text) VALUES (?, ?)");
foreach ($comments as $c) {
    $stmt->execute([$post_id, $c]);
}
