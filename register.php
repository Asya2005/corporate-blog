<?php
$error = $_GET['error'] ?? '';
$name = $_GET['name'] ?? '';
$email = $_GET['email'] ?? '';
$phone = $_GET['phone'] ?? '';
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація</title>
    <style>
        body {
            background-color: #f5f6fa;
            font-family: sans-serif;
        }
        .container {
            width: 300px;
            background: white;
            margin: 40px auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input, button {
            width: 100%;
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #6c5ce7;
            color: white;
            border: none;
            font-weight: bold;
        }
        button:hover {
            background-color: #574bd4;
        }
        .error {
            background: #ffe6e6;
            color: #cc0000;
            border: 1px solid #ff4d4d;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .link-btn {
            display: inline-block;
            padding: 10px;
            background: #ccc;
            color: black;
            text-align: center;
            border-radius: 6px;
            text-decoration: none;
        }
    </style>
</head>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>


<body>

<div class="container">
    <h2>Реєстрація</h2>

    <?php if ($error): ?>
        <div class="error">❌ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="store_user.php" method="POST">
        <input type="text" name="name" placeholder="Ім’я" value="<?= htmlspecialchars($name) ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
        <input type="text" name="phone" placeholder="Телефон (+380...)" value="<?= htmlspecialchars($phone) ?>" required>
        <input type="password" id="password" name="password" placeholder="Пароль" required>
<div style="position: relative; width: 100%;">
    <span onclick="togglePassword()" style="
        position: absolute;
        right: 10px;
        top: -42px;
        cursor: pointer;
        font-size: 18px;
    " title="Показати/сховати пароль">👁</span>

        <button type="submit">Зареєструватись</button>
    </form>

    <a href="login.php" class="link-btn">Увійти</a>
</div>

</body>
</html>
