<?php
session_start();

// Перевіряємо, чи користувач увійшов у систему
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Перевіряємо, чи користувач вже вказав свій рівень
if (isset($_SESSION['english_level'])) {
    $user_level = $_SESSION['english_level'];
} else {
    // Якщо користувач не вказав рівень, перенаправляємо його на сторінку профілю
    header("Location: profile.php");
    exit();
}

// Підключаємося до бази даних
include 'db.php';

// Запит до бази даних для отримання унікальних підрівнів для конкретного рівня
$stmt = $conn->prepare("SELECT DISTINCT sublevel FROM tests WHERE level = :level ORDER BY sublevel ASC");
$stmt->bindParam(':level', $user_level);
$stmt->execute();
$sublevels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Отримуємо максимальний пройдений підрівень користувача для цього рівня
$stmt_max_sublevel = $conn->prepare("SELECT MAX(sublevel) AS max_sublevel FROM test_results WHERE user_id = :user_id AND level = :level");
$stmt_max_sublevel->bindParam(':user_id', $_SESSION['user_id']);
$stmt_max_sublevel->bindParam(':level', $user_level);
$stmt_max_sublevel->execute();
$max_sublevel_result = $stmt_max_sublevel->fetch(PDO::FETCH_ASSOC);
$max_sublevel = $max_sublevel_result['max_sublevel'] ?? 0;

// Визначаємо наступний рівень, який користувач може пройти
$next_level = $max_sublevel + 1;
?>

<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <title>Вибір підрівня</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'menu.php'; ?>
    <div class="container">

        <h2>Рівень: <?php echo htmlspecialchars($user_level); ?></h2>
        <ul class="sublevels">
            <?php foreach ($sublevels as $sublevel) : ?>
                <form action="tests.php" method="get">
                    <input type="hidden" name="level" value="<?php echo urlencode($user_level); ?>">
                    <input type="hidden" name="sublevel" value="<?php echo urlencode($sublevel['sublevel']); ?>">
                    <button type="submit">
                        <?php echo htmlspecialchars("Lesson " . $sublevel['sublevel']); ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>