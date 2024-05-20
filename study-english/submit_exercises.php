<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answers']) && isset($_POST['level'])) {
    $user_answers = $_POST['answers'];
    $level = $_POST['level'];

    // Правильні відповіді для кожного рівня
    $correct_answers = [
        'Beginner' => [
            'What is the capital of France?' => 'Paris',
            'Which one is a fruit?' => 'Apple'
        ],
        'Intermediate' => [
            'Translate: "Cat"' => 'Chat',
            'What is the square root of 64?' => '8'
        ],
        'Advanced' => [
            'Solve: 5x - 3 = 2' => '1',
            'What is the chemical symbol for gold?' => 'Au'
        ]
    ];

    $correct = 0;
    $total = count($user_answers);
    $level_answers = $correct_answers[$level];

    foreach ($user_answers as $question_hash => $user_answer) {
        foreach ($level_answers as $question => $correct_answer) {
            if (md5($question) == $question_hash && $user_answer == $correct_answer) {
                $correct++;
                break;
            }
        }
    }

    $_SESSION['results'] = [
        'correct' => $correct,
        'total' => $total,
        'level' => $level
    ];

    header("Location: results.php");
    exit();
} else {
    header("Location: levels.php");
    exit();
}
?>
