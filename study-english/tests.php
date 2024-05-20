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
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Тести</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <?php include 'menu.php'; ?>
        <h2>Тести для рівня: <?php echo htmlspecialchars($level); ?>, підрівень: <?php echo htmlspecialchars($sublevel); ?></h2>
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
            <button type="submit">Завершити тест</button>
        </form>
    </div>
</body>
</html>
