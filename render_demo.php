<?php
session_start();
require_once 'Page.php';
require_once 'User.php';
require_once 'AdminUser.php';

$user = new User(1, "Олена", "olena@example.com");
$admin = new AdminUser(2, "Остап", "admin@nure.ua", "superadmin");

$page = new Page("Демонстрація класів");

$page->ShowHeader();
?>

<div style="max-width:800px; margin:30px auto; background:#f8f8ff; padding:20px; border-radius:10px;">
    <h2>👤 Інформація про користувачів</h2>

    <h3>Звичайний користувач</h3>
    <p><?= $user->getInfo() ?></p>

    <h3>Адміністратор</h3>
    <p><?= $admin->getInfo() ?></p>
    <p><?= $admin->moderatePosts() ?></p>
</div>

<?php
$page->ShowFooter();
?>
