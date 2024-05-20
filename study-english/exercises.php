<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['level'])) {
    header("Location: levels.php");
    exit();
}

$level = $_GET['level'];

// Приклад завдань для кожного рівня
$exercises = [
    'Beginner' => [
        'What is the capital of France?' => ['Paris', 'London', 'Berlin', 'Rome'],
        'Which one is a fruit?' => ['Apple', 'Carrot', 'Potato', 'Broccoli']
    ],
    'Intermediate' => [
        'Translate: "Cat"' => ['Gato', 'Chien', 'Chat', 'Perro'],
        'What is the square root of 64?' => ['6', '8', '7', '9']
    ],
    'Advanced' => [
        'Solve: 5x - 3 = 2' => ['1', '2', '0', '-1'],
        'What is the chemical symbol for gold?' => ['Au', 'Ag', 'Pb', 'Fe']
    ]
];

$current_exercises = $exercises[$level];
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вправи для рівня <?php echo htmlspecialchars($level); ?></title>
</head>
<body>
<?php include 'menu.php'; ?>
    <h2>Вправи для рівня <?php echo htmlspecialchars($level); ?></h2>
    <form method="POST" action="submit_exercises.php">
        <input type="hidden" name="level" value="<?php echo htmlspecialchars($level); ?>">
        <?php foreach ($current_exercises as $question => $answers): ?>
            <fieldset>
                <legend><?php echo htmlspecialchars($question); ?></legend>
                <?php foreach ($answers as $answer): ?>
                    <label>
                        <input type="radio" name="answers[<?php echo md5($question); ?>]" value="<?php echo htmlspecialchars($answer); ?>" required>
                        <?php echo htmlspecialchars($answer); ?>
                    </label><br>
                <?php endforeach; ?>
            </fieldset>
        <?php endforeach; ?>
        <button type="submit">Здати вправу</button>
    </form>
    <a href="levels.php">Назад до вибору рівня</a>
</body>
</html>
