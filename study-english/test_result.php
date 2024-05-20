<?php
session_start();

// Перевіряємо, чи користувач увійшов у систему
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$score = $_GET['score'];
$total = $_GET['total'];
$results = $_SESSION['test_results'];
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Результати тесту</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <?php include 'menu.php'; ?>
        <h2>Результати тесту</h2>
        <p>Ви відповіли правильно на <?php echo htmlspecialchars($score); ?> з <?php echo htmlspecialchars($total); ?> питань.</p>
        
        <?php foreach ($results as $result): ?>
            <div class="test-question">
                <h3><?php echo htmlspecialchars($result['question_text']); ?></h3>
                <ul>
                    <?php foreach ($result['answers'] as $key => $answer): ?>
                        <li class="test-answer <?php echo ($key == $result['correct_answer']) ? 'correct' : (($key == $result['user_answer'] && $key != $result['correct_answer']) ? 'incorrect' : ''); ?>">
                            <?php echo htmlspecialchars($answer); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
        
        <a href="profile.php">Повернутися до профілю</a>
    </div>
</body>
</html>
