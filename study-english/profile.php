<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Отримання даних з форми
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];
    $english_level = $_POST['english_level'];
    $about_me = $_POST['about_me'];

    // Перевірка, чи введений вік є числом
    if (!is_numeric($age)) {
        $error = "Введіть правильний вік";
    } else {
        // Оновлення даних користувача в базі даних
        try {
            $stmt = $conn->prepare("UPDATE users SET email = ?, phone = ?, age = ?, english_level = ?, about_me = ? WHERE id = ?");
            $stmt->execute([$email, $phone, $age, $english_level, $about_me, $_SESSION['user_id']]);


            $_SESSION['english_level'] = $english_level;
            // Перенаправлення на сторінку зміни профілю з параметром успіху
            header("Location: profile.php?success=1");
            exit();
        } catch (PDOException $e) {
            $error = "Помилка: " . $e->getMessage();
        }
    }
}

// Отримання існуючих даних профілю користувача
try {
    $stmt = $conn->prepare("SELECT email, phone, age, english_level, about_me FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Помилка: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <title>Профіль</title>
    
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Стилі для форми */
        form {
            width: 50%;
            margin: auto;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
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


        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <h2 style="text-align: center;">Профіль</h2>
    <?php
    // Вивід повідомлення про успішне оновлення профілю
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<p class='success'>Дані збережені успішно!</p>";
    }
    // Вивід повідомлення про помилку
    if (isset($error)) echo "<p class='error'>$error</p>";
    ?>
    <form method="post">
        <label for="email">Електронна пошта:</label><br>
        <input type="email" id="email" name="email" value="<?php echo $profile['email']; ?>"><br>

        <label for="phone">Номер телефону:</label><br>
        <input type="text" id="phone" name="phone" value="<?php echo $profile['phone']; ?>"><br>

        <label for="age">Вік:</label><br>
        <input type="text" id="age" name="age" value="<?php echo $profile['age']; ?>"><br>

        <label for="english_level">Рівень англійської:</label><br>
        <select id="english_level" name="english_level">
            <option value="Beginner" <?php if ($profile['english_level'] == "Beginner") echo 'selected'; ?>>Початковий</option>
            <option value="Intermediate" <?php if ($profile['english_level'] == "Intermediate") echo 'selected'; ?>>Середній</option>
            <option value="Advanced" <?php if ($profile['english_level'] == "Advanced") echo 'selected'; ?>>Високий</option>
        </select><br>


        <label for="about_me">Про мене:</label><br>
        <textarea id="about_me" name="about_me"><?php echo $profile['about_me']; ?></textarea><br>

        <input type="submit" value="Зберегти">
    </form>
</body>

</html>