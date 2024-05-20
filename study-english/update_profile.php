<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Підключення до бази даних
require_once "db.php";

// Отримання даних з форми профілю
$email = $_POST['email'];
$phone = $_POST['phone'];
$age = $_POST['age'];
$english_level = $_POST['english_level'];
$about_me = $_POST['about_me'];

// Оновлення даних профілю в базі даних
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE users SET email = :email, phone = :phone, age = :age, english_level = :english_level, about_me = :about_me WHERE id = :user_id");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':english_level', $english_level);
    $stmt->bindParam(':about_me', $about_me);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    // Повідомлення про успішне оновлення профілю
    echo "Дані профілю успішно оновлено!";
} catch(PDOException $e) {
    echo "Помилка: " . $e->getMessage();
}
?>
