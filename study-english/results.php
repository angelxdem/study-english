<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Підключення до бази даних
require_once "db.php";

try {
    // Отримуємо результати проходження тестів користувачем з бази даних
    $stmt = $conn->prepare("SELECT * FROM test_results WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Помилка: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Результати</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <h2 class="container">Результати тестів</h2>
    <table class="container">
        <thead>
            <tr>
                <th>Рівень</th>
                <th>Підрівень</th>
                <th>Дата проходження</th>
                <th>Результат</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td><?php echo $result['level']; ?></td>
                    <td><?php echo $result['sublevel']; ?></td>
                    <td><?php echo $result['date_taken']; ?></td>
                    <td><?php echo $result['score']; ?>/<?php echo $result['total_questions']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
