<?php
session_start();
require 'db.php';

$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

// Перевірка авторства
if (!$post || $post['author_id'] != $_SESSION['user_id']) {
    die("⛔ Доступ заборонено");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $update = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    $update->execute([$title, $content, $id]);

    header("Location: view_post.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагувати пост</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #6c5ce7;
            margin-bottom: 20px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            margin-top: 20px;
            background: #6c5ce7;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .post-image {
            max-width: 100%;
            margin-top: 20px;
            border-radius: 10px;
        }

        a.back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #3498db;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✏️ Редагувати пост</h2>

    <form method="POST">
        <label>Заголовок:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>

        <label>Опис (контент):</label>
        <textarea name="content" rows="6" required><?= htmlspecialchars($post['content']) ?></textarea>

        <?php if (!empty($post['image'])): ?>
            <label>Поточне зображення:</label><br>
            <img src="<?= htmlspecialchars($post['image']) ?>" alt="Зображення" class="post-image">
        <?php endif; ?>

        <button type="submit">💾 Зберегти зміни</button>

    </form>

    <a href="view_post.php?id=<?= $post['id'] ?>" class="back-link">← Назад до посту</a>
</div>

</body>
</html>
