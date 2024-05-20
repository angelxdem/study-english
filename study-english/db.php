<?php
$host = 'localhost';
$dbname = 'study-english';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Встановлення режиму обробки помилок
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Помилка підключення до бази даних: " . $e->getMessage();
    die();
}
?>