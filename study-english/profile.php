<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Отримання даних з форми
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];
    $english_level = $_POST['english_level'];
    $about_me = $_POST['about_me'];

    // Перевірка, чи введений вік є числом
    if (!is_numeric($age)) {
        $error = "Please enter a valid age";
    } else {
        // Оновлення даних користувача в базі даних
        try {
            $stmt = $conn->prepare("UPDATE users SET email = ?, phone = ?, age = ?, english_level = ?, about_me = ? WHERE id = ?");
            $stmt->execute([$email, $phone, $age, $english_level, $about_me, $_SESSION['user_id']]);


            $_SESSION['english_level'] = $english_level;
            // Перенаправлення на сторінку зміни профілю з параметром успіху
            header("Location: profile.php?success=1");
            exit();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Отримання існуючих даних профілю користувача
try {
    $stmt = $conn->prepare("SELECT email, phone, age, english_level, about_me FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            margin-top: 120px;
        }
        .container {
            width: 50%;
            margin: auto;
            margin-top: 50px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        /* Form styles */
        form {
            width: 100%;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="email"],
        textarea,
        select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        /* Error message */
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h2>Profile</h2>
        <?php
        // Display message for successful profile update
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo "<p class='success'>Data saved successfully!</p>";
        }
        // Display error message
        if (isset($error)) echo "<p class='error'>$error</p>";
        ?>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $profile['email']; ?>">

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" value="<?php echo $profile['phone']; ?>">

            <label for="age">Age:</label>
            <input type="text" id="age" name="age" value="<?php echo $profile['age']; ?>">

            <label for="english_level">English Level:</label>
            <select id="english_level" name="english_level">
                <option value="Beginner" <?php if ($profile['english_level'] == "Beginner") echo 'selected'; ?>>Beginner</option>
                <option value="Intermediate" <?php if ($profile['english_level'] == "Intermediate") echo 'selected'; ?>>Intermediate</option>
                <option value="Advanced" <?php if ($profile['english_level'] == "Advanced") echo 'selected'; ?>>Advanced</option>
            </select>

            <label for="about_me">About Me:</label>
            <textarea id="about_me" name="about_me"><?php echo $profile['about_me']; ?></textarea>

            <input type="submit" value="Save">
        </form>
    </div>
</body>

</html>
