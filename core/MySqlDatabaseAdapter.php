<?php
require_once 'DatabaseAdapter.php';

class MySqlDatabaseAdapter implements DatabaseAdapter {
    private $pdo;

    public function connect() {
        $this->pdo = new PDO("mysql:host=localhost;dbname=your_db", "root", "");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function query(string $sql) {
        return $this->pdo->query($sql);
    }
}
