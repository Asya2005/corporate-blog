<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = (int)$_POST['post_id'];
    $pdo->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?")->execute([$post_id]);
}

header('Location: index.php');
exit;
