<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once "classes/Page.php";

class ChatPage extends Page {
    public function ShowContent() {
        global $pdo;
        $messages = $pdo->query("SELECT * FROM chat_messages ORDER BY id ASC LIMIT 50")->fetchAll();
        ?>
        <style>
            .btn-purple {
                padding: 14px 28px;
                background: #6c5ce7;
                color: white;
                font-weight: bold;
                border: none;
                border-radius: 12px;
                transition: 0.2s;
                box-shadow: 0 4px 10px rgba(108, 92, 231, 0.2);
                cursor: pointer;
            }

            .btn-purple:hover {
                background: #5947c0;
                box-shadow: 0 6px 14px rgba(108, 92, 231, 0.3);
            }

            #chat-box::-webkit-scrollbar {
                width: 6px;
            }

            #chat-box::-webkit-scrollbar-thumb {
                background-color: #d0c8f7;
                border-radius: 3px;
            }

            #chat-box::-webkit-scrollbar-track {
                background-color: transparent;
            }
        </style>

        <main style="padding: 40px; max-width: 900px; margin: auto;">
            <h2 style="color:#5a4bd6; text-align:center; font-size:36px; margin-bottom:30px;">💬 Онлайн-чат</h2>

            <div style="text-align:center; margin-bottom: 25px;">
                <label for="username" style="font-weight:bold; color:#3c2c72;">Введіть ваше ім’я:</label><br>
                <input id="username" type="text" placeholder="Наприклад: Аня"
                    style="margin-top: 10px; padding: 14px 20px; width: 300px; border: 1px solid #ccc; border-radius: 10px;">
                <button onclick="setName()" class="btn-purple">Задати ім’я</button>
            </div>

            <div style="text-align: center; margin-bottom: 20px;">
                <label for="recipient" style="font-weight:bold; color:#3c2c72; margin-right: 10px;">Отримувач:</label>
                <select id="recipient" style="padding:12px 18px; border:1px solid #ccc; border-radius:10px;">
                    <option value="ALL">Всі</option>
                </select>
            </div>

            <div id="chat-box" style="
                background: #f9f8fd;
                border: 1px solid #ddd;
                border-radius: 20px;
                padding: 25px;
                height: 400px;
                overflow-y: auto;
                box-shadow: 0 6px 18px rgba(0,0,0,0.05);
                margin-bottom: 25px;
            ">
                <?php foreach ($messages as $msg): ?>
                    <div style="
                        background: #eee6fa;
                        padding: 12px 18px;
                        border-radius: 12px;
                        margin: 10px auto;
                        max-width: 60%;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                        color: #3c2c72;
                    ">
                        <div style="font-weight:bold;"><?= htmlspecialchars($msg['sender']) ?></div>
                        <div style="margin:4px 0;"><?= htmlspecialchars($msg['message']) ?></div>
                        <div style="font-size:12px; color:#666;"><?= date("H:i", strtotime($msg['created_at'])) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="display: flex; gap: 12px;">
                <input id="msg" type="text" placeholder="Ваше повідомлення..."
                    style="flex: 1; padding: 16px 20px; font-size: 16px; border: 1px solid #ccc; border-radius: 12px;">
                <button onclick="sendMessage()" class="btn-purple">Надіслати</button>
            </div>
        </main>

        <script>
            let ws = new WebSocket("ws://localhost:8080");
            let username = "Гість";

            ws.onopen = () => {
                console.log("✅ WebSocket з'єднання встановлено");
            };

            ws.onmessage = (event) => {
                const data = JSON.parse(event.data);
                const chatBox = document.getElementById("chat-box");

                if (data.userList) {
                    const select = document.getElementById("recipient");
                    select.innerHTML = '<option value="ALL">Всі</option>';
                    data.userList.forEach(name => {
                        if (name !== username) {
                            const option = document.createElement("option");
                            option.value = name;
                            option.textContent = name;
                            select.appendChild(option);
                        }
                    });
                    return;
                }

                if (data.type === "notification") {
    const notification = document.createElement("div");
    notification.textContent = `🔔 ${data.text} (${data.time})`;
    notification.style.position = "fixed";
    notification.style.top = "20px";
    notification.style.right = "20px";
    notification.style.background = "#6c5ce7";
    notification.style.color = "white";
    notification.style.padding = "12px 20px";
    notification.style.borderRadius = "10px";
    notification.style.boxShadow = "0 4px 12px rgba(0,0,0,0.2)";
    notification.style.zIndex = "9999";
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 6000);
}


                if (data.joined) {
                    const joinMsg = document.createElement("div");
                    joinMsg.style.background = "#f3f0fb";
                    joinMsg.style.borderRadius = "10px";
                    joinMsg.style.padding = "10px";
                    joinMsg.style.marginBottom = "10px";
                    joinMsg.style.fontStyle = "italic";
                    joinMsg.style.color = "#5a4bd6";
                    joinMsg.style.textAlign = "center";
                    joinMsg.innerText = `${data.joined} приєднався до чату`;
                    chatBox.appendChild(joinMsg);
                    return;
                }

                if (data.from && data.text) {
                    if (data.to === "ALL" || data.to === username || data.from === username) {
                        const bubble = document.createElement("div");
                        bubble.style.background = "#eee6fa";
                        bubble.style.padding = "12px 18px";
                        bubble.style.borderRadius = "12px";
                        bubble.style.margin = "10px auto";
                        bubble.style.maxWidth = "60%";
                        bubble.style.boxShadow = "0 2px 6px rgba(0,0,0,0.1)";
                        bubble.style.color = "#3c2c72";
                        bubble.innerHTML = `
                            <div style="font-weight:bold;">${data.from}</div>
                            <div style="margin:4px 0;">${data.text}</div>
                            <div style="font-size:12px; color:#666;">${data.time}</div>
                        `;
                        chatBox.appendChild(bubble);
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                }
            };

            function setName() {
                username = document.getElementById("username").value || "Гість";
                ws.send(JSON.stringify({ setName: username }));
            }

            function sendMessage() {
                const msg = document.getElementById("msg").value;
                const to = document.getElementById("recipient").value;
                if (msg.trim()) {
                    ws.send(JSON.stringify({ text: msg, to: to }));
                    document.getElementById("msg").value = "";
                }
            }
        </script>
        <?php
    }
}

$page = new ChatPage("Чат");
$page->ShowHeader();
$page->ShowContent();
$page->ShowFooter();
