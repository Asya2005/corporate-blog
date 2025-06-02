<?php
session_start();
require 'db.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$search = trim($_GET['q'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT posts.*, users.name AS author_name, users.id AS author_id FROM posts JOIN users ON posts.author_id = users.id WHERE posts.title LIKE :q OR posts.content LIKE :q ORDER BY created_at DESC");
    $stmt->execute(['q' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT posts.*, users.name AS author_name, users.id AS author_id FROM posts JOIN users ON posts.author_id = users.id ORDER BY created_at DESC");
}
$posts = $stmt->fetchAll();

$user_id = $_SESSION['user_id'];

// Отримання непрочитаних повідомлень
$notifications = $pdo->prepare("SELECT posts.id, posts.title, users.name AS author_name
    FROM notifications
    JOIN posts ON notifications.post_id = posts.id
    JOIN users ON posts.author_id = users.id
    WHERE notifications.user_id = ? AND notifications.is_read = 0");
$unread = $notifications->fetchAll();
$unread_count = count($unread);

$user_id = $_SESSION['user_id'] ?? null;
$avatar = null;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT avatar FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $avatar = $stmt->fetchColumn();
}
?>


<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Корпоративний блог</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background: #6c5ce7;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        nav {
            background: white;
            padding: 10px 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: #6c5ce7;
            text-decoration: none;
            font-weight: bold;
            margin-left: 15px;
        }

        nav form {
            display: inline-flex;
            align-items: center;
        }

        nav input[type="text"] {
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        nav button {
            padding: 6px 12px;
            margin-left: 5px;
            background: #6c5ce7;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .center-button {
            text-align: center;
            margin: 30px auto;
        }

        .center-button a {
            background-color: #6c5ce7;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: background 0.3s ease;
        }

        .center-button a:hover {
            background-color: #5a4bcf;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .site-info {
            background: #fffbe6;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .main-layout {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .news-sidebar {
            flex: 1;
            max-width: 300px;
        }

        .news-block {
            background-color: #f0f4ff;
            border-left: 5px solid #6c63ff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .posts-section {
            flex: 3;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .news-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }

        .news-card img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .like-button {
            background: none;
            border: none;
            color: #e74c3c;
            font-size: 16px;
            cursor: pointer;
        }

        .comment-link {
            color: #3498db;
            text-decoration: none;
            font-size: 16px;
            margin-left: 10px;
        }

        .comments-preview {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }

        footer {
            margin-top: 40px;
            text-align: center;
            background: #6c5ce7;
            color: white;
            padding: 15px;
        }
        .notif-link {
  color: #6c5ce7;
  text-decoration: none;
  font-weight: bold;
  padding: 5px 10px;
}
.notif-link:hover {
  text-decoration: underline;
}

.profile-wrapper {
    margin-left: auto; /* штовхає вправо */
    display: flex;
    justify-content: flex-end;
    width: 100%;
    padding-right: 20px; /* або 0 для повністю впритул */
}

.profile-block {
    display: flex;
    align-items: center;
    gap: 12px;
}

.profile-inline {
    display: flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    font-weight: bold;
    color: #6c5ce7;
}

.header-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #ccc;
}

.logout-link {
    text-decoration: none;
    color: #6c5ce7;
    font-weight: bold;
}

    </style>
</head>
<body>

<header>
    📰 Корпоративний Блог
</header>

<nav>
    <div style="display: flex; align-items: center;">
        Вітаємо, <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>!
        <form method="GET" style="margin-left: 30px;">
            <input type="text" name="q" placeholder="🔍 Пошук..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Пошук</button>
        </form>
    </div>
    <div>
        <a href="create_post.php">➕ Створити пост</a>
        <a href="chat.php" style="
    display: inline-block;
    background-color: #6c5ce7;
    color: white;
    padding: 12px 20px;
    margin: 10px 5px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
">💬 Чат</a>
<a href="notifications.php" class="notif-link">🔔 Сповіщення</a>

        <a href="users.php">👥 Усі користувачі</a>
        <a href="profile.php" style="display:flex;align-items:center;gap:8px;">
    <a href="profile.php" style="display:flex;align-items:center;gap:8px;">
    <a href="profile.php" class="profile-link">

    <div class="profile-wrapper">
  <div class="profile-block">
    <a href="profile.php" class="profile-inline">
      <?php if (!empty($avatar)): ?>
          <img src="uploads/<?= htmlspecialchars($avatar) ?>" alt="Аватар" class="header-avatar">
      <?php else: ?>
          👤
      <?php endif; ?>
      <span>Мій профіль</span>
    </a>
    <a href="logout.php" class="logout-link">📕 Вийти</a>
  </div>
</div>
</nav>

<!-- 👇 КНОПКА ПО ЦЕНТРУ -->
<div class="center-button">
    <a href="sqlite_demo.php">🗃️ Перейти до форми SQLite</a>
</div>

<div class="container">

<?php if ($unread_count > 0): ?>
    <div style="background:#fff3cd; border:1px solid #ffeeba; padding:15px; border-radius:10px; margin-bottom:25px;">
        🔔 У вас <?= $unread_count ?> нове(их) повідомлення:
        <ul style="margin:10px 0 0 20px;">
            <?php foreach ($unread as $n): ?>
                <li><a href="view_post.php?id=<?= $n['id'] ?>">
                    <strong><?= htmlspecialchars($n['author_name']) ?></strong>: <?= htmlspecialchars($n['title']) ?>
                </a></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


    <div class="site-info">
        <h3>ℹ️ Про корпоративний блог</h3>
        <p>Цей сайт — внутрішній блог компанії, створений для комунікації між працівниками:</p>
        <ul>
            <li>📰 Створення та перегляд новин компанії</li>
            <li>💬 Коментарі та взаємодія з колегами</li>
            <li>📂 Додавання постів з фото</li>
            <li>👥 Перегляд профілів та списку співробітників</li>
            <li>🔍 Пошук по публікаціях</li>
        </ul>
    </div>

    <div class="main-layout">
        <div class="news-sidebar">
            <div class="news-block">
                <h2>📰 Новини</h2>
                <div class="news-item">
                    <strong>🗓️ 1 червня, 10:00 — Загальний збір офісу!</strong>
                    <p>Шановні працівники, обов’язкова присутність усіх.</p>
                </div>
            </div>
        </div>

        <div class="posts-section">
            <h2>Останні публікації<?= $search ? ' за запитом: ' . htmlspecialchars($search) : '' ?></h2>

            <?php if (empty($posts)): ?>
                <p>Поки що немає публікацій.</p>
            <?php else: ?>
                <div class="news-grid">
                    <?php foreach ($posts as $post): ?>
                        <div class="news-card">
                            <?php if (!empty($post['image'])): ?>
                                <a href="view_post.php?id=<?= $post['id'] ?>">
                                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="Зображення посту">
                                </a>
                            <?php endif; ?>
                            <h3>
                                <a href="view_post.php?id=<?= $post['id'] ?>" style="text-decoration:none; color:inherit;">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h3>
                            <small>
                                Автор: <a href="user_profile.php?id=<?= $post['author_id'] ?>">
                                    <?= htmlspecialchars($post['author_name']) ?>
                                </a> |
                                <?= $post['created_at'] ?>
                            </small>
                            <p><?= nl2br(htmlspecialchars(mb_strimwidth($post['content'], 0, 200, '...'))) ?></p>
                            <form method="POST" action="like_post.php" style="display:inline;">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <button type="submit" class="like-button">❤️ <?= $post['likes'] ?></button>
                            </form>
                            <a href="view_post.php?id=<?= $post['id'] ?>" class="comment-link">💬 Коментувати</a>
                            <?php
                            $comment_stmt = $pdo->prepare("SELECT comments.*, users.name AS commenter_name FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at ASC LIMIT 3");
                            $comment_stmt->execute([$post['id']]);
                            $comments = $comment_stmt->fetchAll();
                            ?>
                            <?php if ($comments): ?>
                                <div class="comments-preview">
                                    <h4>Коментарі:</h4>
                                    <?php foreach ($comments as $comment): ?>
                                        <div class="comment">
                                            <strong><?= htmlspecialchars($comment['commenter_name']) ?>:</strong>
                                            <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                                            <small><?= $comment['created_at'] ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<footer>
    &copy; <?= date("Y") ?> Корпоративний блог. Всі права захищено.
</footer>

</body>
</html>
