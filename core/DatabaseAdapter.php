<?php
interface DatabaseAdapter {
    public function connect();
    public function query(string $sql);
}
