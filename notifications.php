<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Отримуємо непрочитані сповіщення
$stmt = $pdo->prepare("
    SELECT notifications.id AS notif_id, posts.title, posts.created_at 
    FROM notifications
    JOIN posts ON notifications.post_id = posts.id
    WHERE notifications.user_id = ? AND notifications.is_read = 0
    ORDER BY posts.created_at DESC
");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();

// Позначаємо як прочитані
$pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?")->execute([$user_id]);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Сповіщення</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .notifications-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .notification {
            padding: 15px;
            margin-bottom: 10px;
            border-left: 5px solid #6a5acd;
            background: #f7f5ff;
        }
        .notification-title {
            font-weight: bold;
            color: #3c2c72;
        }
        .notification-date {
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="notifications-container">
        <h2>Ваші нові сповіщення</h2>

        <?php if (empty($notifications)): ?>
            <p>Немає нових сповіщень.</p>
        <?php else: ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="notification">
                    <div class="notification-title">Нова публікація: <?= htmlspecialchars($notif['title']) ?></div>
                    <div class="notification-date">Опубліковано: <?= htmlspecialchars($notif['created_at']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
