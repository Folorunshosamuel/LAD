<?php
include 'db_connect.php'; // Make sure this path matches your connection file
include 'header.php';

// Initialize an empty message variable
$successMessage = '';
$warningMessage = '';
$errorMessage = '';

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the bill ID from the URL
$billId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($billId === null) {
    die("Bill ID is not defined in the URL.");
}

// Fetch the bill’s data
$query = $db->prepare("SELECT bills.*, legislators.name AS sponsor_name, committees.name AS committee_name 
                       FROM bills
                       LEFT JOIN legislators ON bills.sponsor_id = legislators.id
                       LEFT JOIN committees ON bills.committeeReferred = committees.id
                       WHERE bills.id = ?");
$query->execute([$billId]);
$bill = $query->fetch();

if (!$bill) {
    die("Bill not found.");
}

// Ensure the bill view is logged only once per session
if (!isset($_SESSION['viewed_bills'])) {
    $_SESSION['viewed_bills'] = []; // Initialize if not set
}

if (!in_array($billId, $_SESSION['viewed_bills'])) {
    logActivity($db, $_SESSION['id'], 'Viewed Bill with ID: ' . $billId);
    $_SESSION['viewed_bills'][] = $billId; // Mark this bill as viewed
}

// Handle the Track Bill action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['track_bill'])) {
    $userId = $_SESSION['id']; // Assuming the user's ID is stored in the session

    // Check if the bill is already tracked
    $checkQuery = $db->prepare("SELECT * FROM tracked_bills WHERE user_id = ? AND bill_id = ?");
    $checkQuery->execute([$userId, $billId]);

    if ($checkQuery->rowCount() === 0) {
        // Add the bill to the tracked list
        $insertQuery = $db->prepare("INSERT INTO tracked_bills (user_id, bill_id) VALUES (?, ?)");
        
        if ($insertQuery->execute([$userId, $billId])) {
            // Set the success message to display in the HTML
            $successMessage = "Bill successfully tracked!";
        } else {
            $errorMessage = "Error tracking the bill. Please try again later!";
        }
    } else {
        $warningMessage = "You are already tracking this bill!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Required meta tags -->

    <!-- Twitter -->
    <meta name="twitter:site" content="@legis360">
    <meta name="twitter:creator" content="@legis30">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Legis360">
    <meta name="twitter:description" content="Legis360 Providing a 360-degree view of parliamentary activities">
    <meta name="twitter:image" content="https://www.legis360.org/">

    <!-- Facebook -->
    <meta property="og:url" content="https://www.legis360.org">
    <meta property="og:title" content="Legis360">
    <meta property="og:description" content="Legis360 Providing a 360-degree view of parliamentary activities">

    <meta property="og:image" content="#">
    <meta property="og:image:secure_url" content="#">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Legis360 Providing a 360-degree view of parliamentary activities">
    <meta name="author" content="Tenstepscreative">

    <title><?= htmlspecialchars($bill['title']) ?></title>
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
            font-size: 24px;
            margin-bottom: 5px;
            text-align: left;
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
        .bill-section{
            margin-top: 20px;
            font-size: 15px;
            color: #333;
            text-transform: capitalize;
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
        .track-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .track-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="bill-container">
        <!-- Display success message if available -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php elseif ($warningMessage): ?>
            <div class="alert alert-warning">
                <?= htmlspecialchars($warningMessage) ?>
            </div>
        <?php elseif ($errorMessage): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>
        <div class="bill-header">
            <h1><?= htmlspecialchars($bill['billNum']) ?> - <?= htmlspecialchars($bill['title']) ?></h1>
        </div>
        <div class="bill-section">
            <p><?= nl2br(htmlspecialchars($bill['billAnalysis']) ?: "No analysis available.") ?></p>
        </div>

        <div class="bill-details">
            <p><strong>Sponsored by:</strong> <?= htmlspecialchars($bill['sponsor_name'] ?? "Unknown") ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($bill['billStatus']) ?></p>
        </div>
        <div class="bill-details">
            <p><strong>First Reading:</strong> <?= htmlspecialchars($bill['firstReading'] ?? 'Not reached') ?></p>
            <p><strong>Second Reading:</strong> <?= htmlspecialchars($bill['secondReading'] ?? 'Not reached') ?></p>
            <p><strong>Committee referred to:</strong> <?= htmlspecialchars($bill['committee_name'] ?? 'Not reached' ) ?></p>
            <p><strong>Third Reading:</strong> <?= htmlspecialchars($bill['thirdReading'] ?? 'Not reached') ?></p>
            <p><strong>Consolidated with:</strong> <?= htmlspecialchars($bill['consolidatedWith'] ?? 'Not reached' ) ?></p>
        </div>

        <div class="bill-section">
            <?php if ($bill['billFile']): ?>
                <a href="<?= htmlspecialchars($bill['billFile']) ?>" class="bttn" download>Download Bill Document</a>
            <?php else: ?>
                <p>No bill document available for download.</p>
            <?php endif; ?>

            <!-- Edit Bill Button -->
            <?php if ($user_role === 'admin'): ?>
                <a href="edit-bill.php?id=<?= urlencode($billId) ?>" class="bttn">Edit Bill</a>
            <?php endif; ?>

            <!-- Track Bill Form -->
            <form method="POST">
                <button type="submit" name="track_bill" class="track-button">Track Bill</button>
            </form>
        </div>
    </div>
</body>
</html>
