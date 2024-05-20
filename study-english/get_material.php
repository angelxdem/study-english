<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $section = $_POST["section"];
    $topic = $_POST["topic"];

    try {
        $stmt = $conn->prepare("SELECT material FROM materials WHERE section = ? AND topic = ?");
        $stmt->execute([$section, $topic]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($result);
    } catch(PDOException $e) {
        echo "Помилка: " . $e->getMessage();
    }
}
?>
