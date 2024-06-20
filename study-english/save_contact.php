<?php
// Підключення до бази даних
require_once 'db.php';

// Перевірка, чи дані були відправлені методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Перевірка наявності та отримання даних з форми
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';

    try {
        // Перевірка наявності таблиці
        $stmt = $conn->query("SHOW TABLES LIKE 'contacts'");
        $tableExists = $stmt->rowCount() > 0;

        if ($tableExists) {
            // Підготовка SQL-запиту для вставки даних
            $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)");
            // Передача параметрів та виконання запиту
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            $stmt->execute();

            // Редирект на головну сторінку
            header("Location: index.php");
            exit();
        } else {
            echo "Помилка: Таблиця 'contacts' не існує";
        }
    } catch (PDOException $e) {
        echo "Помилка: " . $e->getMessage();
    }
} else {
    // Якщо дані не були відправлені методом POST, вивести повідомлення про помилку
    echo "Помилка: Дані не були відправлені методом POST";
}
?>
