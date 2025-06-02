<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT id, name, email FROM users ORDER BY name");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Усі користувачі</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
        }

        header {
            background-color: #6c5ce7;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 700px;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
            color: #6c5ce7;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        a {
            text-decoration: none;
            color: #2d3436;
            font-weight: bold;
        }

        a:hover {
            color: #6c5ce7;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #6c5ce7;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: bold;
        }

        .back-link:hover {
            background-color: #5a4cc9;
        }
    </style>
</head>
<body>

<header>
    👥 Усі користувачі
</header>

<div class="container">
    <h2>Список учасників</h2>

    <?php if (empty($users)): ?>
        <p>Немає зареєстрованих користувачів.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <a href="user_profile.php?id=<?= $user['id'] ?>">
                        <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a class="back-link" href="index.php">⬅ Назад до головної</a>
</div>


<?php
class User
{
    public $id;
    public $name;
    public $email;
    public $is_active;

    public function __construct($id, $name, $email, $is_active = true)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->is_active = $is_active;
    }

    
    public function getStatus()
    {
        return $this->is_active ? "✅ Активний користувач" : "⛔ Заблокований";
    }
}
?>


</body>
</html>
