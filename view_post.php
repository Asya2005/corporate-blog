<?php
session_start();
require 'db.php';

$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    die("❌ Пост не знайдено");
}

// Отримуємо пост
$stmt = $pdo->prepare("
    SELECT posts.*, users.name AS author_name, users.id AS author_id
    FROM posts
    JOIN users ON posts.author_id = users.id
    WHERE posts.id = ?
");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    die("❌ Пост не існує");
}

// Обробка нового коментаря
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])) {
    $content = trim($_POST['comment']);
    if ($content) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $_SESSION['user_id'], $content]);
        header("Location: view_post.php?id=$post_id");
        exit;
    }
}
// Позначити всі сповіщення про цей пост як прочитані для поточного користувача
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND post_id = ?");
    $stmt->execute([$_SESSION['user_id'], $_GET['id']]);
}


// Отримуємо коментарі
$comments_stmt = $pdo->prepare("
    SELECT comments.*, users.name AS commenter_name
    FROM comments
    JOIN users ON comments.user_id = users.id
    WHERE comments.post_id = ?
    ORDER BY comments.created_at ASC
");
$comments_stmt->execute([$post_id]);
$comments = $comments_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?> — Корпоративний блог</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f9;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        h1 {
            color: #6c5ce7;
        }

        .post-meta {
            color: #888;
            margin-bottom: 20px;
        }

        .post-image {
            max-width: 100%;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .edit-delete {
            margin-top: 15px;
        }

        .edit-delete a {
            margin-right: 10px;
            font-size: 14px;
            color: orange;
            text-decoration: none;
        }

        .edit-delete a.delete {
            color: red;
        }

        .comments {
            margin-top: 40px;
        }

        .comment {
            border-top: 1px solid #ccc;
            padding-top: 10px;
            margin-top: 10px;
        }

        .comment small {
            color: #888;
        }

        form textarea {
            width: 100%;
            padding: 10px;
            height: 100px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        form button {
            margin-top: 10px;
            padding: 10px 20px;
            background: #6c5ce7;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        a.back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #3498db;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="back-link">← Назад до блогу</a>

    <h1><?= htmlspecialchars($post['title']) ?></h1>

    <div class="post-meta">
        Автор: <a href="user_profile.php?id=<?= $post['author_id'] ?>"><?= htmlspecialchars($post['author_name']) ?></a> |
        <?= $post['created_at'] ?>
    </div>

    <?php if (!empty($post['image'])): ?>
        <img src="<?= htmlspecialchars($post['image']) ?>" class="post-image" alt="Зображення поста">
    <?php endif; ?>

    <div class="post-content">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>

    <?php if ($_SESSION['user_id'] == $post['author_id']): ?>
        <div class="edit-delete">
            <a href="edit_post.php?id=<?= $post['id'] ?>">✏️ Редагувати</a>
            <a href="delete_post.php?id=<?= $post['id'] ?>" class="delete" onclick="return confirm('Ви дійсно хочете видалити цей пост?');">🗑️ Видалити</a>
        </div>
    <?php endif; ?>

    <div class="comments">
        <h3>💬 Коментарі (<?= count($comments) ?>)</h3>

        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <strong><?= htmlspecialchars($comment['commenter_name']) ?>:</strong>
                <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                <small><?= $comment['created_at'] ?></small>
            </div>
        <?php endforeach; ?>

        <h4>Додати коментар</h4>
        <form method="POST">
            <textarea name="comment" required placeholder="Напишіть коментар..."></textarea>
            <br>
            <button type="submit">Надіслати</button>
        </form>
    </div>
</div>

</body>
</html>
