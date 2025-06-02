<?php
session_start();
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вхід — Корпоративний блог</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: linear-gradient(120deg, #6c5ce7, #a29bfe);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #2d3436;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #6c5ce7;
        }

        .login-box label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #b2bec3;
            border-radius: 5px;
            outline: none;
        }

        .login-box button {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background: #6c5ce7;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-box button:hover {
            background: #594ae2;
        }

        .error-message {
            color: #d63031;
            background-color: #ffe6e6;
            border: 1px solid #d63031;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Вхід для працівників компанії</h2>
    <a href="register.php" style="display:inline-block; margin-top:10px; padding:10px; background:#ccc; color:#000; border-radius:6px; text-decoration:none;">Зареєструватися</a>


    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="auth.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Увійти</button>
    </form>
</div>

</body>
</html>
