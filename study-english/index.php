<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Головна</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        h2 {
            margin-top: 50px;
        }

        .school-info {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .school-info p {
            font-size: 18px;
            line-height: 1.5;
        }

        .quotes {
            margin-top: 50px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
        }

        .quote {
            width: 45%;
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f0f0f0;
        }

        .quote p {
            font-style: italic;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h2>Ласкаво просимо!</h2>
        <div class="school-info">
            <h3>Наша школа англійської</h3>
            <p>Наша школа пропонує індивідуальні та групові заняття з англійської мови для всіх рівнів. Ми прагнемо зробити процес вивчення англійської мови цікавим та ефективним для кожного студента.</p>
            <img src="image.jpg" alt="Фото нашої школи">
        </div>
        <div class="quotes">
            <div class="quote">
                <p>"Знання англійської мови - це ключ до багатьох можливостей."</p>
                <p>- Невідомий</p>
            </div>
            <div class="quote">
                <p>"Англійська мова - це не тільки інструмент спілкування, це також вікно в світ культури та можливостей."</p>
                <p>- Девід Крістал</p>
            </div>
        </div>
    </div>
</body>
</html>
