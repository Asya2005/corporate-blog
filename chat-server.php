<?php
session_start();

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require 'db.php';
require __DIR__ . '/vendor/autoload.php';

class Chat implements MessageComponentInterface {
    protected $clients = [];
    protected $usernames = [];
    protected $pdo;
    protected $connectionsByUsername = [];

    public function __construct() {
        $host = 'localhost';
        $db = 'corporate_blog';
        $user = 'root';
        $pass = '';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Помилка підключення до БД: " . $e->getMessage() . "\n";
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients[$conn->resourceId] = $conn;
        $this->usernames[$conn->resourceId] = "Гість_" . $conn->resourceId;
        $this->broadcastUserList();

        echo "Нове з'єднання! ({$conn->resourceId})\n";

        $joinedMsg = json_encode(["joined" => $this->usernames[$conn->resourceId]]);
        foreach ($this->clients as $client) {
            if ($client !== $conn) {
                $client->send($joinedMsg);
            }
        }
    }

    private function broadcastUserList() {
        $userList = array_values($this->usernames);
        $data = json_encode(["userList" => $userList]);

        foreach ($this->clients as $client) {
            $client->send($data);
        }
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if (isset($data['setName'])) {
            $this->usernames[$from->resourceId] = htmlspecialchars($data['setName']);
            $this->connectionsByUsername[$this->usernames[$from->resourceId]] = $from;
            $this->broadcastUserList();
            return;
        }

        $sender = $this->usernames[$from->resourceId];
        $text = htmlspecialchars($data['text']);
        $time = date("H:i");
        $to = $data['to'] ?? 'ALL';

        $message = json_encode([
            "from" => $sender,
            "text" => $text,
            "time" => $time,
            "to" => $to
        ]);

        $stmt = $this->pdo->prepare("INSERT INTO chat_messages (sender, message, created_at) VALUES (?, ?, ?)");
$stmt->execute([$sender, $text, date("Y-m-d H:i:s")]);

        if ($to === "ALL") {
            foreach ($this->clients as $client) {
                $client->send($message);
            }
        } elseif (isset($this->connectionsByUsername[$to])) {
            $recipientConn = $this->connectionsByUsername[$to];
            $from->send($message);
            $recipientConn->send($message);
        }
        if (isset($data['notification'])) {
    $notif = htmlspecialchars($data['notification']);
    $message = json_encode([
        "type" => "notification",
        "text" => $notif,
        "time" => date("H:i")
    ]);
    foreach ($this->clients as $client) {
        $client->send($message);
    }
    return;
}
    }

    public function onClose(ConnectionInterface $conn) {
        $name = $this->usernames[$conn->resourceId] ?? null;
        unset($this->clients[$conn->resourceId]);
        unset($this->usernames[$conn->resourceId]);
        if ($name) {
            unset($this->connectionsByUsername[$name]);
        }
        echo "З'єднання {$conn->resourceId} закрито\n";
        $this->broadcastUserList();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Помилка: {$e->getMessage()}\n";
        $conn->close();
    }
}

// 🔽 Запуск WebSocket-сервера
$server = IoServer::factory(
    new HttpServer(new WsServer(new Chat())),
    8080
);

$server->run();
