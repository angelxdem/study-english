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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Навчальні матеріали</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px 20px;
            margin-top: 120px;
        }

        .section {
            margin-bottom: 30px;
            background-color: #f9f9f9;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .section:hover {
            transform: translateY(-5px);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .section h3 {
            padding: 15px;
            margin: 0;
            background-color: #ff6b6b;
            color: #fff;
            border-bottom: 1px solid #ddd;
            border-radius: 10px 10px 0 0;
            cursor: pointer;
        }

        .topics {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        .topic {
            padding: 10px 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.6s ease;
            color: black;
        }

        .topic:hover {
            background-color: #f0f0f0;
        }

        #material-container {
            display: none; /* Приховуємо за замовчуванням */
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
        }

        #material-container h3 {
            margin-top: 0;
        }

        #material-container p {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h2>Study material</h2>
        <?php foreach ($sections as $section): ?>
            <div class="section">
                <h3><?php echo $section; ?></h3>
                <div class="topics">
                    <?php foreach ($topics[$section] as $topic): ?>
                        <button class="topic"><?php echo $topic; ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="material-container" class="container">
        
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var sections = document.querySelectorAll(".section");

            sections.forEach(function(section) {
                section.addEventListener("mouseover", function() {
                    this.style.height = "auto";
                });

                section.addEventListener("mouseout", function() {
                    this.style.height = "fit-content";
                });
            });

            var topics = document.querySelectorAll(".topic");

            topics.forEach(function(topic) {
                topic.addEventListener("click", function() {
                    var section = this.parentNode.previousElementSibling.innerText;
                    var topicText = this.innerText;

                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "get_material.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var response = JSON.parse(this.responseText);
                            if (response && response.material) {
                                var materialContainer = document.getElementById("material-container");

                                // Виведення нового матеріалу на сторінку
                                materialContainer.innerHTML = `
                                    <h3>${section} - ${topicText}</h3>
                                    <p>${response.material}</p>
                                `;

                                // Показуємо блок з матеріалом
                                materialContainer.style.display = "block";

                                // Прокручуємо до блоку з матеріалом
                                materialContainer.scrollIntoView({ behavior: "smooth" });
                            }
                        }
                    };
                    xhr.send("section=" + encodeURIComponent(section) + "&topic=" + encodeURIComponent(topicText));
                });
            });
        });
    </script>
</body>
</html>
