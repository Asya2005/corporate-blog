<?php
class Page {
    public $title;

    // Один-єдиний конструктор
    public function __construct($title = "Корпоративний блог") {
        $this->title = $title;
    }

    // Варіант 1 — HTML-хедер прямо тут:
    public function ShowHeader() {
    echo "<!DOCTYPE html>
    <html lang='uk'>
    <head>
        <meta charset='UTF-8'>
        <title>{$this->title}</title>
        <link rel='stylesheet' href='style.css'>
        <style>
            body {
                margin: 0;
                font-family: 'Segoe UI', sans-serif;
                background-color: #f9f9fe;
                color: #3c2c72;
            }

            header {
                background-color: #6c5ce7;
                padding: 12px 0;
            }

            .navbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }

            .navbar-title {
                font-size: 22px;
                font-weight: bold;
                color: white;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .navbar-title i {
                font-size: 22px;
            }

            .navbar-links {
                display: flex;
                gap: 18px;
            }

            .nav-btn {
                background-color: white;
                color: #6c5ce7;
                text-decoration: none;
                padding: 10px 18px;
                border-radius: 12px;
                font-weight: 500;
                transition: all 0.25s ease;
            }

            .nav-btn:hover {
                background-color: #edeaff;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .nav-btn.active {
                background-color: #a89afa;
                color: white;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <header>
            <div class='navbar'>
                <div class='navbar-title'>📰 {$this->title}</div>
                <nav class='navbar-links'>
                    <a href='index.php' class='nav-btn'>🏠 Головна</a>
                    <a href='create_post.php' class='nav-btn'>➕ Створити пост</a>
                    <a href='chat.php' class='nav-btn active'>💬 Чат</a>
                    <a href='users.php' class='nav-btn'>👥 Усі користувачі</a>
                    <a href='profile.php' class='nav-btn'>👤 Мій профіль</a>
                    <a href='logout.php' class='nav-btn'>📋 Вийти</a>
                </nav>
            </div>
        </header>";
}


    // Якщо хочеш використовувати шаблон
    // public function ShowHeader() {
    //     include "Parts/header.phtml";
    // }

    // Тільки якщо використовується
    public function ShowContent() {
        include "Parts/index.phtml";
    }

    public function ShowFooter() {
        echo "<footer style='background:#6c5ce7; color:white; text-align:center; padding:10px; margin-top:40px;'>
            <p>&copy; 2025 Корпоративний блог. Всі права захищено.</p>
        </footer>
        </body></html>";
    }

    // Або, якщо шаблонний футер
    // public function ShowFooter() {
    //     include "Parts/footer.phtml";
    // }
}
?>
