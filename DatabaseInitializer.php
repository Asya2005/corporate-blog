<?php
namespace App;

use PDO;
use PDOException;

class DatabaseInitializer {
    public function __construct(PDO $pdo) {
        try {
            $pdo->beginTransaction();

            $pdo->exec("CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT,
                email TEXT
            )");

            $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT,
                content TEXT,
                author_id INTEGER
            )");

            $pdo->exec("CREATE TABLE chat_messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                sender TEXT NOT NULL,
                message TEXT NOT NULL,
                created_at TEXT NOT NULL
            )");


            $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
            $stmt->execute(["Test User", "test@example.com"]);

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "❌ Не вдалося ініціалізувати базу: " . $e->getMessage();
        }
    }
}
