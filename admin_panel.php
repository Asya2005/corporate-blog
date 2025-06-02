<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete_post'])) {
    $postId = (int)$_GET['delete_post'];
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$postId]);
    header("Location: admin_panel.php");
    exit;
}

if (isset($_GET['delete_comment'])) {
    $commentId = (int)$_GET['delete_comment'];
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);
    header("Location: admin_panel.php");
    exit;
}

$stmt = $pdo->query("SELECT posts.*, users.name AS author_name FROM posts JOIN users ON posts.author_id = users.id ORDER BY posts.created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT comments.*, users.name AS author_name, posts.title AS post_title
                     FROM comments 
                     JOIN users ON comments.user_id = users.id
                     JOIN posts ON comments.post_id = posts.id
                     ORDER BY comments.created_at DESC");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Адмін-панель</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eee6fa;
            color: #3c2c72;
            padding: 30px;
        }

        h1 {
            font-size: 28px;
            color: #3c2c72;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 22px;
            margin-top: 40px;
            margin-bottom: 10px;
            color: #3c2c72;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 25px;
            padding: 10px 18px;
            background-color: #3c2c72;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(60, 44, 114, 0.2);
        }

        th {
            background-color: #c2bfe4;
            color: #3c2c72;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f2ecfc;
        }

        .delete-btn {
            background-color: transparent;
            border: none;
            color: #e74c3c;
            font-weight: bold;
            cursor: pointer;
            text-decoration: underline;
        }

        .delete-btn:hover {
            color: #c0392b;
        }
    </style>
</head>
<body>

    <h1>Адмін-панель</h1>
    <a href="index.php" class="back-btn">⬅ Назад на головну</a>

    <h2>Список постів</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Заголовок</th>
                <th>Автор</th>
                <th>Дата</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post['id']) ?></td>
                    <td><?= htmlspecialchars($post['title']) ?></td>
                    <td><?= htmlspecialchars($post['author_name']) ?></td>
                    <td><?= htmlspecialchars($post['created_at']) ?></td>
                    <td>
                        <a class="delete-btn" href="admin_panel.php?delete_post=<?= $post['id'] ?>" onclick="return confirm('Видалити пост?')">Видалити</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Список коментарів</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Пост</th>
                <th>Автор</th>
                <th>Текст</th>
                <th>Дата</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?= htmlspecialchars($comment['id']) ?></td>
                    <td><?= htmlspecialchars($comment['post_title']) ?></td>
                    <td><?= htmlspecialchars($comment['author_name']) ?></td>
                    <td><?= htmlspecialchars($comment['content']) ?></td>
                    <td><?= htmlspecialchars($comment['created_at']) ?></td>
                    <td>
                        <a class="delete-btn" href="admin_panel.php?delete_comment=<?= $comment['id'] ?>" onclick="return confirm('Видалити коментар?')">Видалити</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
