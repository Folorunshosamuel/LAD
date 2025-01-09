<?php
// File: dashboard.php

// Include database connection
include('db_connect.php');
include('header.php');

// Start session to get logged-in user ID
$userId = $_SESSION['id'] ?? null;

// Redirect to login if user is not logged in
if (!$userId) {
    header('Location: login.php');
    exit();
}

// Function to get stats from the database for the logged-in user
function getDashboardStats($db, $userId) {
    $response = [
        'billsTracked' => 0,
        'legislatorsTracked' => 0,
        'committeeActivities' => 0,
        'notifications' => []
    ];

    try {
        // Query to get total bills tracked by the user
        $stmt = $db->prepare("SELECT COUNT(*) AS total FROM tracked_bills WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $response['billsTracked'] = (int)$row['total'];
        }

        // Query to get total legislators tracked by the user
        $stmt = $db->prepare("SELECT COUNT(*) AS total FROM tracked_legislators WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $response['legislatorsTracked'] = (int)$row['total'];
        }

        // Query to get total committee tracked by the user
        $stmt = $db->prepare("SELECT COUNT(*) AS total FROM tracked_committees WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $response['committeeActivities'] = (int)$row['total'];
        }

        // Query to get notifications for the user
        $stmt = $db->prepare("SELECT message, created_at FROM notifications WHERE user_id = :userId ORDER BY created_at DESC LIMIT 5");
        $stmt->execute(['userId' => $userId]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response['notifications'][] = $row;
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    return $response;
}

// Fetch stats for the logged-in user
$dashboardStats = getDashboardStats($db, $userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="lad.css">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <style>
        .disclaimer-card {
            background-color: #f8faff; /* Light blue background */
            border: none; /* Remove default border */
            border-radius: 8px; /* Rounded corners */
            padding: 30px;
            margin: 70px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Light shadow */
        }

        .disclaimer-card .card-title {
            font-size: 1.5rem; /* Larger title font */
            font-weight: bold; /* Bold text */
            color: #333; /* Dark text color */
            margin-bottom: 10px; /* Space below the title */
        }

        .disclaimer-card .card-text {
            font-size: 1rem; /* Normal text size */
            color: #666; /* Medium text color */
            line-height: 1.6; /* Better line spacing */
        }

    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Total Bills Tracked -->
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-primary shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Bills Tracked</h5>
                        <h3 id="billsTrackedCount">0</h3>
                    </div>
                </div>
            </div>
            <!-- Legislators Tracked -->
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-success shadow">
                    <div class="card-body">
                        <h5 class="card-title">Legislators Tracked</h5>
                        <h3 id="legislatorsTrackedCount">0</h3>
                    </div>
                </div>
            </div>
            <!-- Notifications Section -->
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-warning shadow">
                    <div class="card-body">
                        <h5 class="card-title">Committees Tracked</h5>
                        <h3 id="legislatorsTrackedCount">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card disclaimer-card">
        <div class="card-body">
            <h5 class="card-title">Disclaimer</h5>
            <p class="card-text">
            The information provided by the Legislative Analysis Dashboard (LAD), a digital democracy platform, regarding Nigeria's National Assembly, is intended solely for general informational purposes. It is not produced, endorsed, or sanctioned by the government of Nigeria or any of its agencies.
            The Legislative Analysis Dashboard (LAD) operates independently, and the views and interpretations presented are entirely those of LAD and do not reflect any official government stance or policy. Any use of this information is entirely at your discretion.
            </p>
        </div>
    </div>

    <script>
        // Load stats dynamically on page load
        document.addEventListener("DOMContentLoaded", () => {
            // Get stats from PHP
            const dashboardStats = <?php echo json_encode($dashboardStats); ?>;

            // Update the UI
            document.getElementById("billsTrackedCount").textContent = dashboardStats.billsTracked || 0;
            document.getElementById("legislatorsTrackedCount").textContent = dashboardStats.legislatorsTracked || 0;
            document.getElementById("committeesTrackedCount").textContent = dashboardStats.committeesTracked || 0;

            // Update notifications
            const notificationList = document.getElementById("notificationList");
            notificationList.innerHTML = '';
            if (dashboardStats.notifications.length > 0) {
                dashboardStats.notifications.forEach(notification => {
                    const listItem = document.createElement("li");
                    listItem.textContent = `${notification.message} (${notification.created_at})`;
                    notificationList.appendChild(listItem);
                });
            } else {
                notificationList.innerHTML = '<li>No notifications available</li>';
            }
        });
    </script>
</body>
</html>
