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

$recentBillsQuery = $db->query("SELECT id, billNum, title, chamber, billStatus, firstReading FROM bills ORDER BY firstReading DESC LIMIT 3");
$recentBills = $recentBillsQuery->fetchAll();

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
            $successMessage = "Bill is now been tracked!";
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
    <title><?= htmlspecialchars($bill['title']) ?></title>
    <link rel="stylesheet" href="lad.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            max-width: 1100px;
            margin: auto;
            gap: 20px;
        }
        .bill-card {
            flex: 3;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .sidebar {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .bill-header h1 {
            font-size: 24px;
            color: #333;
        }
        .icon {
            color: #007bff;
            margin-right: 10px;
        }
        .bill-details p {
            font-size: 16px;
            margin: 8px 0;
        }
        .buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .bttn, .track-button {
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .bttn {
            background: #007bff;
            color: white;
        }
        .bttn:hover {
            background: #0056b3;
        }
        .track-button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .track-button:hover {
            background: #218838;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="bill-card">
            <div class="bill-header">
            <form method="POST">
                <button type="submit" name="track_bill" class="track-button"><i class="fa-solid fa-bookmark"></i> Track Bill</button>
            </form>
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
                <h1><i class="fa-solid fa-file-invoice icon"></i><?= htmlspecialchars($bill['billNum']) ?> - <?= htmlspecialchars($bill['title']) ?></h1>
            </div>
            <div class="bill-details text-muted">
                <p><i class="fa-solid fa-user icon"></i><strong>Sponsored by:</strong> <?= htmlspecialchars($bill['sponsor_name'] ?? "Unknown") ?></p>
                <p><i class="fa-solid fa-gavel icon"></i><strong>Status:</strong> <?= htmlspecialchars($bill['billStatus']) ?></p>
                <p><i class="fa-solid fa-calendar-days icon"></i><strong>First reading:</strong> <?= htmlspecialchars($bill['firstReading'] ?? 'Not reached') ?></p>
                <p><i class="fa-solid fa-calendar-check icon"></i><strong>Second reading:</strong> <?= htmlspecialchars($bill['secondReading'] ?? 'Not reached') ?></p>
                <p><i class="fa-solid fa-calendar-check icon"></i><strong>Third reading:</strong> <?= htmlspecialchars($bill['thirdReading'] ?? 'Not reached') ?></p>
                <p><i class="fa-solid fa-landmark icon"></i><strong>Committee referred:</strong> <?= htmlspecialchars($bill['committee_name'] ?? 'Not assigned') ?></p>
            </div>
            <div class="buttons">
                <?php if ($bill['billFile']): ?>
                    <a href="<?= htmlspecialchars($bill['billFile']) ?>" class="bttn" download><i class="fa-solid fa-download"></i> Download Bill</a>
                <?php else: ?>
                    <p>No bill document available.</p>
                <?php endif; ?>
                <?php if ($user_role === 'admin'): ?>
                    <a href="edit-bill.php?id=<?= urlencode($billId) ?>" class="bttn"><i class="fa-solid fa-edit"></i> Edit Bill</a>
                <?php endif; ?>
            </div>
            
        </div>
        <div class="sidebar">
            <h3><i class="fa-solid fa-scroll icon"></i> Related Bills</h3>
            <div class="card-body p-0">
                <div class="list-group">
                    <?php if (!empty($recentBills)) : ?>
                        <?php foreach ($recentBills as $bill) : ?>
                            <a href="bill_1.php?id=<?= urlencode($bill['id']) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                
                                    
                                    <div class="ms-2 me-auto">
                                <div style="font-size: 1rem;"><?= htmlspecialchars($bill['billNum']) ?>: <?= htmlspecialchars($bill['title']) ?></div>
                                </div>
                                
                                
                            </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="list-group-item text-center text-muted">
                            No recent bills available.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
