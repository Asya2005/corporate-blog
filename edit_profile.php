<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT name, email, phone, avatar FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагувати профіль</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f0f8;
        }

        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        input, button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #7e57c2;
            color: white;
            font-weight: bold;
        }

        button:hover {
            background-color: #6744b1;
        }

        .avatar-preview {
            text-align: center;
            margin-bottom: 20px;
        }

        .avatar-preview img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Редагувати профіль</h2>

    <div class="avatar-preview">
        <img id="avatarPreview" src="<?= !empty($user['avatar']) ? 'uploads/' . htmlspecialchars($user['avatar']) : 'uploads/default.png' ?>" alt="Прев'ю аватара">
    </div>

    <form method="post" action="update_profile.php" enctype="multipart/form-data">
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

        <label>Аватар:</label>
        <input type="file" name="avatar" id="avatarInput" accept="image/*">

        <button type="submit">💾 Зберегти</button>
    </form>
</div>

<script>
document.getElementById('avatarInput').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>

</body>
</html>
