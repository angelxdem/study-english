<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Підключення до бази даних
require_once "db.php";

try {
    // Отримуємо результати проходження тестів користувачем з бази даних
    $stmt = $conn->prepare("SELECT * FROM test_results WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Помилка: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Підключення jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Підключення бібліотеки DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <!-- Підключення бібліотеки DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <!-- Підключення бібліотеки Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Стилі для блоку діаграми */
        #chart-container {
            text-align: center;
        }

        canvas {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div class="container">
        <h2>Test Results</h2>
        <table id="results-table">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Sublevel</th>
                    <th>Date Taken</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result) : ?>
                    <tr>
                        <td><?php echo $result['level']; ?></td>
                        <td><?php echo $result['sublevel']; ?></td>
                        <td><?php echo $result['date_taken']; ?></td>
                        <td><?php echo $result['score']; ?>/<?php echo $result['total_questions']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Block for pie chart -->
        <div id="chart-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        // JavaScript для ініціалізації DataTables
        $(document).ready(function() {
            $('#results-table').DataTable({
                "searching": false, // Вимкнути пошук
                "lengthChange": false, // Вимкнути вибір кількості записів
                "paging": false,
                "info": false,
            });
        });

        // JavaScript для створення кругової діаграми
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Passed', 'Failed'],
                    datasets: [{
                        label: 'Test Results',
                        data: [
                            <?php
                            $passed = 0;
                            $failed = 0;
                            foreach ($results as $result) {
                                if ($result['score'] >= 4) {
                                    $passed++;
                                } else {
                                    $failed++;
                                }
                            }
                            echo $passed . ', ' . $failed;
                            ?>
                        ],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(255, 99, 132, 0.8)',
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        text: 'Test Success Rate'
                    }
                }
            });
        });
    </script>
</body>
</html>
