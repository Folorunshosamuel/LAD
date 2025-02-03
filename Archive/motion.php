<?php
// Database connection
include 'db_connect.php'; // Make sure this path matches your connection file

// Get the bill ID from the URL
$motionId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($motionId === null) {
    die("Motion ID is not defined in the URL.");
}

// Fetch the bill’s data
$query = $db->prepare("SELECT Motions.*, legislators.name AS sponsor_name FROM Motions
                       LEFT JOIN legislators ON Motions.sponsor_id = legislators.id
                       WHERE Motions.id = ?");
$query->execute([$motionId]);
$motion = $query->fetch();

if (!$motion) {
    die("Motion not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motion Details - <?= htmlspecialchars($motion['title']) ?></title>
    <link rel="stylesheet" href="lad.css"> <!-- Adjust path to your CSS file -->

    <style>
        .bill-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .bill-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .bill-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .bill-header p {
            font-size: 18px;
            color: #666;
        }
        .bill-details p {
            margin: 8px 0;
            font-size: 16px;
        }
        .bill-section h3 {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
        }
        .download-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .download-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
    <div class="bill-container">
        <div class="bill-header">
            <h1><?= htmlspecialchars($motion['title']) ?></h1>
        </div>

        <div class="bill-details">
            <p><strong>Sponsored by:</strong> <?= htmlspecialchars($motion['sponsor_name'] ?? "Unknown") ?></p>
            <p><strong>Chamber:</strong> <?= htmlspecialchars($motion['chamber']) ?></p>
            <p><strong>Date Filed:</strong> <?= htmlspecialchars($motion['dateFiled'] ?? 'N/A') ?></p>
        </div>
        <div class="bill-details">
            <p><strong>Committee referred to:</strong> <?= htmlspecialchars($motion['committeeReferred'] ?? 'N/A' ) ?></p>
            <p><strong>Committee report date:</strong> <?= htmlspecialchars($motion['committeeReportDate'] ?? 'N/A' ) ?></p>
        </div>

        <div class="bill-section">
            <h3>Motion resolution</h3>
            <p><?= nl2br(htmlspecialchars($motion['resolution']) ?: "No resolution available.") ?></p>
        </div>

        <div class="bill-section">
            <?php if ($motion['motionFile']): ?>
                <a href="uploads/<?= htmlspecialchars($motion['motionFile']) ?>" class="download-link" download>Download Motion Document</a>
            <?php else: ?>
                <p>No Motion document available for download.</p>
            <?php endif; ?>

            <!-- Edit Bill Button -->
            <a href="edit-motion.php?id=<?= urlencode($motionId) ?>" class="edit-link">Edit Motion</a>
        </div>
    </div>
</body>
</html>
