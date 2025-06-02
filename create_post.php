<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageName = basename($_FILES['image']['name']);
    $targetDir = 'uploads/';
    $targetFile = $targetDir . $imageName;

    // створити папку, якщо не існує
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $image = $targetFile;
    }
}

    $author_id = $_SESSION['user_id'];

    // 👉 ВСТАВКА ПОСТА з author_id
    $stmt = $pdo->prepare("INSERT INTO posts (title, content, image, author_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $content, $image, $author_id]);
    $post_id = $pdo->lastInsertId();

    // ✅ Додаємо сповіщення всім користувачам, КРІМ автора
    $users_stmt = $pdo->prepare("SELECT id FROM users WHERE id != ?");
    $users_stmt->execute([$author_id]);
    $users = $users_stmt->fetchAll();

    $noti_stmt = $pdo->prepare("INSERT INTO notifications (user_id, post_id) VALUES (?, ?)");
    foreach ($users as $user) {
        $noti_stmt->execute([$user['id'], $post_id]);
    }

    header("Location: index.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Створити новий пост</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(120deg, #a29bfe, #81ecec);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 90%;
        }

        h2 {
            text-align: center;
            color: #6c5ce7;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        button {
            background: #6c5ce7;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #594ae2;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #2d3436;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>📝 Новий пост</h2>

    <form method="POST" enctype="multipart/form-data">
        <label for="title">Заголовок:</label>
        <input type="text" name="title" id="title" required>

        <label for="content">Опис (контент):</label>
        <textarea name="content" id="content" required></textarea>

        <label for="image">Зображення:</label>
        <input type="file" name="image" id="image" accept="image/*">

        <button type="submit">📤 Опублікувати</button>
    </form>

    <a href="index.php" class="back-link">← Назад до головної</a>
</div>

</body>
</html>
