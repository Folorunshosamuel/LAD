<?php
// Include your database connection file here
include 'db_connect.php';

// Query to get the count of members by political party
$query = $db->query("SELECT party, COUNT(id) AS count FROM legislators where chamber='House of reps' GROUP BY party");
$partyCounts = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 100%; margin: auto;">
        <canvas id="partyChart"></canvas>
    </div>

    <script>
        // PHP data passed to JavaScript
        const data = <?php echo json_encode($partyCounts); ?>;

        // Prepare labels and counts for the chart
        const labels = data.map(item => item.party);
        const counts = data.map(item => item.count);

        // Create the chart
        const ctx = document.getElementById('partyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Members',
                    data: counts,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Members'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Political Parties'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
