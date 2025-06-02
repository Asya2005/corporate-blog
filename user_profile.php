<?php
session_start();
require 'db.php';

if (!isset($_GET['id'])) {
    echo "Користувач не вказаний.";
    exit;
}

$user_id = (int)$_GET['id'];

// Отримуємо інформацію про користувача
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Користувача не знайдено.";
    exit;
}

// Отримуємо всі пости користувача
$stmt = $pdo->prepare("SELECT * FROM posts WHERE author_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Профіль користувача</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        header {
            background: #6c5ce7;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .user-info h2 {
            margin-top: 0;
        }

        .user-info p {
            color: #555;
        }

        .posts {
            margin-top: 30px;
        }

        .post-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .post-card img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .post-card h3 {
            margin: 0 0 5px;
            color: #6c5ce7;
        }

        .post-card small {
            color: #888;
        }

        .back-link {
            text-decoration: none;
            color: #6c5ce7;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<header>
    👤 Профіль користувача
</header>

<div class="container">
    <div class="user-info">
        <h2><?= htmlspecialchars($user['name']) ?></h2>
        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
        <p>Кількість постів: <?= count($posts) ?></p>
    </div>

    <div class="posts">
        <h3>Публікації:</h3>
        <?php if (!$posts): ?>
            <p>Цей користувач ще не створив публікацій.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <?php if ($post['image']): ?>
                        <img src="<?= htmlspecialchars($post['image']) ?>" alt="Зображення посту">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                    <small><?= $post['created_at'] ?></small>
                    <p><?= nl2br(htmlspecialchars(mb_strimwidth($post['content'], 0, 200, '...'))) ?></p>
                    <a href="view_post.php?id=<?= $post['id'] ?>">Переглянути повністю</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <a class="back-link" href="index.php">⬅ Назад до головної</a>
</div>

</body>
</html>
