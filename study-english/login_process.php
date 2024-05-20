<?php
include 'db.php';
session_start();

// Перевіряємо, чи користувач вже увійшов у систему
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $user);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['english_level'] = $user['english_level'];
            header("Location: index.php");
            exit();
        } else {
            echo "Невірний логін або пароль!";
        }
    } catch (PDOException $e) {
        echo "Помилка: " . $e->getMessage();
    }
}
?>
