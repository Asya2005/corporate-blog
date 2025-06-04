<?php
require_once 'DatabaseAdapter.php';

class SQLiteDatabaseAdapter implements DatabaseAdapter {
    private $db;

    public function connect() {
        $this->db = new SQLite3(__DIR__ . '/../chat.sqlite');
    }

    public function query(string $sql) {
        return $this->db->query($sql);
    }
}
