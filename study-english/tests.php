<?php
session_start();

// Перевіряємо, чи користувач увійшов у систему
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$level = $_GET['level'];
$sublevel = $_GET['sublevel'];

include 'db.php';

$stmt = $conn->prepare("SELECT * FROM tests WHERE level = :level AND sublevel = :sublevel");
$stmt->bindParam(':level', $level);
$stmt->bindParam(':sublevel', $sublevel);
$stmt->execute();
$tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tests</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
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

        .test-question {
            margin-bottom: 20px;
        }

        .test-question h3 {
            margin-bottom: 10px;
        }

        .test-answer {
            display: block;
            margin-bottom: 10px;
        }

        .test-answer input[type="radio"] {
            margin-right: 10px;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            background-color: #4caf50;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h2>Tests for Level: <?php echo htmlspecialchars($level); ?>, Sublevel: <?php echo htmlspecialchars($sublevel); ?></h2>
        <form action="submit_test.php" method="POST">
            <?php foreach ($tests as $test): ?>
                <div class="test-question">
                    <h3><?php echo htmlspecialchars($test['question_text']); ?></h3>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <label class="test-answer">
                            <input type="radio" name="questions[<?php echo $test['id']; ?>][answer]" value="<?php echo $i; ?>" required>
                            <?php echo htmlspecialchars($test["answer_$i"]); ?>
                        </label>
                    <?php endfor; ?>
                </div>
            <?php endforeach; ?>
            <input type="hidden" name="level" value="<?php echo htmlspecialchars($level); ?>">
            <input type="hidden" name="sublevel" value="<?php echo htmlspecialchars($sublevel); ?>">
            <button type="submit">Complete Test</button>
        </form>
    </div>
</body>
</html>
