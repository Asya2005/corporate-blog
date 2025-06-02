<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Отримуємо дані користувача
$stmt = $pdo->prepare("SELECT name, email, phone, avatar FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Рахуємо кількість постів користувача
$stmt = $pdo->prepare("SELECT COUNT(*) AS post_count FROM posts WHERE author_id = ?");
$stmt->execute([$user_id]);
$count = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Мій профіль</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f0f8;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #6c5ce7;
            padding: 15px;
            text-align: center;
            color: white;
            font-size: 20px;
        }

        .profile-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .profile-container h2 {
            color: #6c5ce7;
            margin-bottom: 20px;
        }

        .profile-container p {
            font-size: 16px;
            margin: 10px 0;
        }

        strong {
            color: #6c5ce7;
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #7e57c2;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .back-button:hover {
            background-color: #6c5ce7;
        }

        .btn-admin {
  display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #7e57c2;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
}
    </style>
</head>
<body>

<header>👤 Мій профіль</header>

<div class="profile-container">
    <?php if (!empty($user['avatar'])): ?>
    <div style="text-align:center; margin-bottom:20px;">
        <img src="uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="Аватар" style="width:120px;height:120px;border-radius:50%;object-fit:cover;box-shadow:0 0 6px rgba(0,0,0,0.2);">
    </div>
<?php endif; ?>
    <h2><?= htmlspecialchars($user['name']) ?></h2>
    <p>Ім’я: <?= htmlspecialchars($user['name']) ?></p>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Телефон: <?= htmlspecialchars($user['phone']) ?></p>
    <p><strong>Кількість публікацій:</strong> <?= $count ?></p>
    <a href="index.php" class="back-button">⬅ Повернутися на головну</a>
    <a href="edit_profile.php" class="back-button">✏️ Редагувати профіль</a>
     <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="admin_panel.php" class="btn-admin">🛠 Перейти до адмін-панелі</a>
<?php endif; ?>


</div>

</body>
</html>
