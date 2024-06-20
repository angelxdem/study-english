<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $english_level = $_POST['english_level']; // Додано рівень англійської мови

    try {
        // Перевірка, чи введений логін вже існує в базі даних
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $user);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            echo "Користувач з таким логіном вже існує!";
        } else {
            // Додавання нового користувача до бази даних
            $stmt = $conn->prepare("INSERT INTO users (username, password, english_level) VALUES (:username, :password, :english_level)");
            $stmt->bindParam(':username', $user);
            $stmt->bindParam(':password', $pass);
            $stmt->bindParam(':english_level', $english_level); // Додано рівень англійської мови
            if ($stmt->execute()) {
                // Встановлення сесійної змінної для нового користувача
                session_start();
                $_SESSION['user_id'] = $conn->lastInsertId(); // Отримання ID нового користувача
                $_SESSION["english_level"] = $english_level;
                header("Location: index.php");
                exit();
            } else {
                echo "Помилка реєстрації!";
            }
        }
    } catch (PDOException $e) {
        echo "Помилка: " . $e->getMessage();
    }
}

// Виведення повідомлення про успішну реєстрацію після редиректу
if (isset($_SESSION['success_message'])) {
    echo $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>
