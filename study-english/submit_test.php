<?php
session_start();

// Перевіряємо, чи користувач увійшов у систему
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$level = $_POST['level'];
$sublevel = $_POST['sublevel'];
$questions = $_POST['questions'];

$total_questions = count($questions);
$correct_answers = 0;
$results = [];

foreach ($questions as $question_id => $question) {
    $user_answer = $question['answer'];
    
    // Отримання правильних відповідей з бази даних
    $stmt = $conn->prepare("SELECT question_text, answer_1, answer_2, answer_3, answer_4, correct_answer FROM tests WHERE id = :id");
    $stmt->bindParam(':id', $question_id);
    $stmt->execute();
    $test = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $correct_answer = $test['correct_answer'];
    if ($user_answer == $correct_answer) {
        $correct_answers++;
    }
    
    $results[] = [
        'question_text' => $test['question_text'],
        'answers' => [
            '1' => $test['answer_1'],
            '2' => $test['answer_2'],
            '3' => $test['answer_3'],
            '4' => $test['answer_4'],
        ],
        'correct_answer' => $correct_answer,
        'user_answer' => $user_answer,
    ];
}

// Запис результату в базу даних
$stmt = $conn->prepare("INSERT INTO test_results (user_id, level, sublevel, score, total_questions) VALUES (:user_id, :level, :sublevel, :score, :total_questions)");
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':level', $level);
$stmt->bindParam(':sublevel', $sublevel);
$stmt->bindParam(':score', $correct_answers);
$stmt->bindParam(':total_questions', $total_questions);
$stmt->execute();

// Збереження результатів у сесії для відображення
$_SESSION['test_results'] = $results;

// Перенаправлення на сторінку з результатами
header("Location: test_result.php?score=$correct_answers&total=$total_questions");
exit();
?>
