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

// Отримуємо результати тестів користувача для цього рівня
$stmt_results = $conn->prepare("SELECT sublevel, score FROM test_results WHERE user_id = :user_id AND level = :level");
$stmt_results->bindParam(':user_id', $_SESSION['user_id']);
$stmt_results->bindParam(':level', $user_level);
$stmt_results->execute();
$results = $stmt_results->fetchAll(PDO::FETCH_ASSOC);

// Перетворюємо результати в асоціативний масив для зручності
$user_results = [];
foreach ($results as $result) {
    $user_results[$result['sublevel']] = $result['score'];
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вибір підрівня</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center; /* Відцентровує контейнер по вертикалі */
            min-height: 100vh; /* Забезпечує розмір контейнера по всій висоті екрану */
        }

        .container {
            max-width: 800px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 120px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sublevels {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .lesson {
            display: block;
            width: 100%;
            padding: 15px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .not-started {
            background-color: #fff;
            color: #333;
        }

        .passed {
            background-color: #4caf50;
            color: #fff;
        }

        .failed {
            background-color: #ff9800;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h2>Level: <?php echo htmlspecialchars($user_level); ?></h2>
        <ul class="sublevels">
            <?php foreach ($sublevels as $sublevel) : ?>
                <?php
                $sublevel_number = $sublevel['sublevel'];
                $class = 'not-started'; // Клас за замовчуванням

                if (isset($user_results[$sublevel_number])) {
                    if ($user_results[$sublevel_number] >= 4) {
                        $class = 'passed';
                    } elseif ($user_results[$sublevel_number] < 4) {
                        $class = 'failed';
                    }
                }
                ?>
                <form action="tests.php" method="get">
                    <input type="hidden" name="level" value="<?php echo urlencode($user_level); ?>">
                    <input type="hidden" name="sublevel" value="<?php echo urlencode($sublevel_number); ?>">
                    <button type="submit" class="lesson <?php echo $class; ?>">
                        <?php echo htmlspecialchars("Lesson " . $sublevel_number); ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
