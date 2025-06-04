<?php
require_once 'ModelSaverInterface.php';

class PostSaver implements ModelSaverInterface {
    public function save(array $data): void {
        require_once 'classes/DB.php';
        $pdo = DB::connect();

        $stmt = $pdo->prepare("INSERT INTO posts (title, content, author_id) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['title'],
            $data['content'],
            $data['author_id']
        ]);
    }
}
