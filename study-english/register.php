<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .register-link {
            text-align: center;
        }
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #fff;
            /* Фоновий колір для вибору */
            font-size: 16px;
            /* Розмір шрифту */
            color: #555;
            /* Колір тексту */
        }

        select:hover {
            border-color: #aaa;
            /* Колір рамки при наведенні */
        }

        /* Стилі для активного вибору */
        select:focus {
            border-color: #4CAF50;
            /* Колір рамки при активному виборі */
            outline: none;
            /* Приховує контурний зовнішній контур */
        }

    </style>
</head>
<body>
<div class="container">
        <h2>Реєстрація</h2>
        <form method="POST" action="register_process.php">
            <label for="username">Логін:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <label for="english_level">Рівень англійської:</label>
            <select id="english_level" name="english_level">
                <option value="Beginner">Початковий</option>
                <option value="Intermediate">Середній</option>
                <option value="Advanced">Високий</option>
            </select>
            <button type="submit">Зареєструватися</button>
        </form>
        <div class="login-link">
            <p>Вже маєте акаунт? <a href="login.php">Увійти</a></p>
        </div>
    </div>
</body>
</html>
