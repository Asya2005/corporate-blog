<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Шаблони проєктування</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            margin: 40px;
        }
        h2 {
            color: #663399;
            margin-top: 60px;
        }
        .box {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        .btn {
            background-color: #9b59b6;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .result {
            background-color: #f3f3f3;
            padding: 12px;
            border-left: 4px solid #2ecc71;
            font-family: monospace;
            white-space: pre-line;
        }
        img {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 10px;
        }
        .caption {
            font-style: italic;
            margin-top: 4px;
            text-align: center;
        }
    </style>
</head>
<body>

    <h1>Реалізація шаблонів проєктування</h1>

    <div class="box">
        <h2>1-2. MVC + Singleton Router</h2>
        <p>Реалізація MVC архітектури з єдиним екземпляром роутера</p>
        <button class="btn">Тест Singleton</button>
        <button class="btn">Тест MVC</button>
        <div class="result">
{
    "same_instance": true,<br>
    "message": "Singleton test completed"
        }
        </div>
        <div class="caption">Реалізація маршрутизації з використанням Singleton</div>
    </div>

    <div class="box">
        <h2>3. Factory Pattern</h2>
        <p>Фабрика для створення різних моделей (Post, User)</p>
        <button class="btn">Тест Factory</button>
        <div class="result">
{
    "post_model": "PostModel",<br>
    "user_model": "UserModel",<br>
    "message": "Factory created models successfully"
        }
        </div>
        <div class="caption">Factory Pattern для створення моделей</div>
    </div>

    <div class="box">
        <h2>4. Strategy Pattern</h2>
        <p>Обчислення цін за різними стратегіями (зі знижкою / без знижки)</p>
        <button class="btn">Тест Strategy</button>
        <div class="result">
{
    "base_price": 100,<br>
    "strategy": "Discount 20%",<br>
    "final_price": 80
        }
        </div>
        <div class="caption">Застосування шаблона Strategy для обчислення ціни</div>
    </div>

    <div class="box">
        <h2>5. Adapter Pattern</h2>
        <p>Адаптери для MySQL і SQLite з єдиним інтерфейсом</p>
        <button class="btn">Тест Adapters</button>
        <div class="result">
MySQL: Connected (записів: 3)<br>
SQLite: Connected (записів: 2)
        </div>
        <div class="caption">Реалізація Adapter Pattern</div>
    </div>

    <div class="box">
        <h2>6. Decorator Pattern</h2>
        <p>Логування операцій моделі перед і після запису до бази</p>
        <button class="btn">Тест Decorator</button>
        <div class="result">
{
    "success": true,<br>
    "message": "Check server logs for logging output"
        }
        </div>
        <div class="caption">Реалізація Decorator Pattern</div>
    </div>

</body>
</html>
