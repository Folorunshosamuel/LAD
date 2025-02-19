<?php
// Include database connection
include 'db_connect.php';

// Fetch bill progression data
$query = $db->query("SELECT title, firstReading, secondReading, thirdReading FROM bills");
$bills = $query->fetchAll(PDO::FETCH_ASSOC);

// Prepare data arrays
$billTitles = [];
$progressStages = [];

foreach ($bills as $bill) {
    $billTitles[] = $bill['title'];

    // Determine the bill's progression stage based on date presence
    if ($bill['thirdReading']) {
        $progressStages[] = 3; // Third Reading (Completed)
    } elseif ($bill['secondReading']) {
        $progressStages[] = 2; // Second Reading
    } elseif ($bill['firstReading']) {
        $progressStages[] = 1; // First Reading
    } else {
        $progressStages[] = 0; // Not Started
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill Progression Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .container { width: 80%; margin: auto; }
    </style>
</head>
<body>
    <div class="container">
        <canvas id="billProgressChart"></canvas>
    </div>

    <script>
        // Chart data prepared in PHP
        const billTitles = <?php echo json_encode($billTitles); ?>;
        const progressStages = <?php echo json_encode($progressStages); ?>;

        // Define progression labels
        const progressionLabels = ['Not Started', 'First Reading', 'Second Reading', 'Third Reading'];

        // Create the chart
        const ctx = document.getElementById('billProgressChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: billTitles,
                datasets: [{
                    label: 'Progression Stage',
                    data: progressStages,
                    backgroundColor: progressStages.map(stage => 
                        stage === 3 ? '#28a745' : stage === 2 ? '#ffc107' : stage === 1 ? '#17a2b8' : '#dc3545'
                    ),
                }]
            },
            options: {
                indexAxis: 'y', // For horizontal bars in Chart.js v3+
                scales: {
                    x: {
                        min: 0,
                        max: 3,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) { return progressionLabels[value]; }
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Bill Progression Through Legislative Stages' }
                }
            }
        });
    </script>
</body>
</html>
