<?php include 'views/layout.php'; ?>

<h2>Останні пости</h2>
<?php foreach ($posts as $post): ?>
    <div>
        <h3><?= htmlspecialchars($post['title']) ?></h3>
        <p><?= htmlspecialchars($post['content']) ?></p>
    </div>
<?php endforeach; ?>
