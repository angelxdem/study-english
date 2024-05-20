<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Підключення до бази даних
require_once "db.php";

try {
    // Отримуємо унікальні розділи
    $stmt = $conn->prepare("SELECT DISTINCT section FROM materials");
    $stmt->execute();
    $sections = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Отримуємо теми для кожного розділу
    $topics = [];
    foreach ($sections as $section) {
        $stmt = $conn->prepare("SELECT topic FROM materials WHERE section = ?");
        $stmt->execute([$section]);
        $topics[$section] = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
} catch(PDOException $e) {
    echo "Помилка: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Навчальні матеріали</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center; /* Центрує текст у контейнері */
        }

        .section {
            display: inline-block;
            margin-right: 20px;
            vertical-align: top;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }

        .section h3 {
            margin-top: 0;
        }

        .topic {
            display: block;
            margin-bottom: 5px;
            padding: 5px 10px;
            background-color: #f0f0f0;
            cursor: pointer;
            border-radius: 5px;
            color: #000;
        }

        .topic:hover {
            background-color: #e0e0e0;
        }

        #material-container {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var topics = document.querySelectorAll(".topic");

    topics.forEach(function(topic) {
        topic.addEventListener("click", function() {
            var section = this.parentNode.querySelector("h3").innerText;
            var topicText = this.innerText;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "get_material.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    if (response && response.material) {
                        // Видалення попереднього матеріалу
                        var existingMaterial = document.querySelector("#material");
                        if (existingMaterial) {
                            existingMaterial.remove();
                        }

                        // Виведення нового матеріалу на сторінку
                        var materialDiv = document.createElement("div");
                        materialDiv.id = "material";
                        materialDiv.innerHTML = "<h3>" + section + " - " + topicText + "</h3><p>" + response.material + "</p>";
                        document.getElementById("material-container").appendChild(materialDiv);
                    }
                }
            };
            xhr.send("section=" + encodeURIComponent(section) + "&topic=" + encodeURIComponent(topicText));
        });
    });
});
</script>

<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h2>Навчальні матеріали</h2>
        <?php foreach ($sections as $section): ?>
            <div class="section">
                <h3><?php echo $section; ?></h3>
                <?php foreach ($topics[$section] as $topic): ?>
                    <button class="topic"><?php echo $topic; ?></button>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="material-container" class="container">
        
    </div>
</body>
</html>
