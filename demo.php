<?php
require_once 'user.php';

$name = $_GET['name'] ?? 'Гість';
$email = $_GET['email'] ?? 'guest@example.com';

$korystuvach = new Korysuvach($name, $email);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Перевірка користувача</title>
    <style>
        body {
            background: linear-gradient(120deg, #e0f7fa, #f1f8e9);
            font-family: 'Segoe UI', sans-serif;
            color: #333;
            padding: 40px;
            margin: 0;
        }

        .card {
            background-color: white;
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            margin-top: 0;
            font-size: 24px;
            color: #00695c;
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #e0f2f1;
            border-left: 4px solid #00796b;
            border-radius: 8px;
        }

        .back-btn {
            margin-top: 25px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #00796b;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #004d40;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Результат перевірки користувача</h2>

        <div class="result">
            <?php
                $korystuvach->pryvitannya();
                $korystuvach->chySluzhbovaPochta();
            ?>
        </div>

        <a class="back-btn" href="javascript:history.back()">← Назад</a>
    </div>
</body>
</html>
