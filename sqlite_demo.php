<?php
class Database {
    private $pdo;

    public function __construct($dbFile) {
        if (!file_exists($dbFile)) {
            touch($dbFile);
        }
        $this->pdo = new PDO("sqlite:$dbFile");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection() {
        return $this->pdo;
    }
}

$db = new Database('sqlite_blog.db');
$pdo = $db->getConnection();

try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            image TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER,
            comment TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
        );
    ");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['new_post'])) {
            $imageName = null;

            if (isset($_FILES) && isset($_FILES['image']) && is_array($_FILES['image']) && !empty($_FILES['image']['name'])) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = 'uploads/' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $imageName);
            }

            $stmt = $pdo->prepare("INSERT INTO posts (title, content, image) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['title'], $_POST['content'], $imageName]);
        }

        if (isset($_POST['edit_post'])) {
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$_POST['title'], $_POST['content'], $_POST['post_id']]);
        }

        if (isset($_POST['delete_post'])) {
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$_POST['post_id']]);
        }

        if (isset($_POST['add_comment'])) {
            $stmt = $pdo->prepare("INSERT INTO comments (post_id, comment) VALUES (?, ?)");
            $stmt->execute([$_POST['post_id'], $_POST['comment']]);
        }

        if (isset($_POST['delete_comment'])) {
            $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$_POST['comment_id']]);
        }

        if (isset($_POST['edit_comment'])) {
            $stmt = $pdo->prepare("UPDATE comments SET comment = ? WHERE id = ?");
            $stmt->execute([$_POST['comment_text'], $_POST['comment_id']]);
        }

        header("Location: sqlite_demo.php");
        exit;
    }

    $posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Помилка бази даних: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Демо SQLite | Блог</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f3f0fa; margin: 0; padding: 20px; }
        header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        header h1 { color: #6a4fb6; }
        .container { display: inline-flex; gap: 30px; align-items: flex-start; }

        .posts, .form {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .post {
            border: 1px solid #e3e0ff;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 12px;
        }

        .post h2 { margin: 0 0 5px; color: #442a90; }
        .date { font-size: 0.85em; color: #888; }

        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            background: #6a4fb6;
            color: white;
            cursor: pointer;
            margin-right: 6px;
        }

        button:hover { background: #5941a9; }

        .comment {
            background: #f5f4ff;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .top-link {
            text-decoration: none;
            background: #e3e0ff;
            padding: 8px 12px;
            border-radius: 8px;
            color: #333;
            font-size: 14px;
        }

        .top-link:hover { background: #d2cefa; }

        img.post-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
        }

        h4 { margin-top: 20px; }
    </style>
</head>
<body>

<header>
    <h1>Демо SQLite: Пости</h1>
    <a href="index.php" class="top-link">← На головну</a>
</header>

<div class="container">
    <div class="posts">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <p class="date"><?= $post['created_at'] ?></p>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <?php if ($post['image']): ?>
                    <img src="<?= htmlspecialchars($post['image']) ?>" class="post-image" alt="Зображення посту">
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>">
                    <textarea name="content"><?= htmlspecialchars($post['content']) ?></textarea>
                    <button name="edit_post">Зберегти</button>
                    <button name="delete_post" onclick="return confirm('Видалити пост?')">Видалити</button>
                </form>

                <h4>Коментарі:</h4>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ?");
                $stmt->execute([$post['id']]);
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $comment): ?>
                    <div class="comment">
                        <div class="date"><?= $comment['created_at'] ?></div>
                        <form method="POST">
                            <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                            <textarea name="comment_text"><?= htmlspecialchars($comment['comment']) ?></textarea>
                            <button name="edit_comment">Зберегти</button>
                            <button name="delete_comment" onclick="return confirm('Видалити коментар?')">Видалити</button>
                        </form>
                    </div>
                <?php endforeach; ?>

                <form method="POST">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <textarea name="comment" placeholder="Ваш коментар..." required></textarea>
                    <button name="add_comment">Додати коментар</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="form">
        <h2>Створити новий пост</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Заголовок" required>
            <textarea name="content" placeholder="Контент..." required></textarea>
            <input type="file" name="image" accept="image/*">
            <button type="submit" name="new_post">Додати</button>
        </form>
    </div>
</div>

</body>
</html>
