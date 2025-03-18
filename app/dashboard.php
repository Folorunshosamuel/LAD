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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_bill'])) {

        $deleteBillQuery = $db->prepare("DELETE FROM tracked_bills WHERE user_id = ? AND bill_id = ?");
        if ($deleteBillQuery->execute([$userId, $billId])) {
            echo "<p style='color: green;'>Bill removed from tracking list.</p>";
        } else {
            echo "<p style='color: red;'>Error removing the bill. Please try again later.</p>";
        }
    }

    if (isset($_POST['remove_legislator'])) {
        // Remove a tracked legislator
        $legislatorId = $_POST['legislator_id'];
        $userId = $_SESSION['id']; // Assuming the user's ID is stored in the session

        $deleteLegislatorQuery = $db->prepare("DELETE FROM tracked_legislators WHERE user_id = ? AND legislator_id = ?");
        if ($deleteLegislatorQuery->execute([$userId, $legislatorId])) {
            echo "<p style='color: green;'>Legislator removed from tracking list.</p>";
        } else {
            echo "<p style='color: red;'>Error removing the legislator. Please try again later.</p>";
        }
    }
}

// Fetch stats for the logged-in user
$dashboardStats = getDashboardStats($db, $userId);


// Handle the Remove Bill action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_bill_id'])) {
    $bill_id = $_POST['remove_bill_id'];

    try {
        $removeQuery = $db->prepare("DELETE FROM tracked_bills WHERE user_id = :user_id AND bill_id = :bill_id");
        $removeQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $removeQuery->bindParam(':bill_id', $bill_id, PDO::PARAM_INT);

        if ($removeQuery->execute()) {
            echo "<p class='text-success'>Bill successfully removed from your tracked list!</p>";
            // Refresh the page to reflect changes
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            echo "<p class='text-danger'>Failed to remove the bill. Please try again.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Error removing bill: {$e->getMessage()}</p>";
    }
}
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
        

        .tracked-card {
            background-color: #f8f9fa; /* Light gray background */
            border: 1px solid #ddd; /* Subtle border */
            border-radius: 8px; /* Rounded corners */
            margin-bottom: 20px; /* Space below the card */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Light shadow */
        }

        .tracked-card .card-header {
            background-color: #e9ecef; /* Slightly darker gray */
            font-size: 1.2rem; /* Larger text */
            font-weight: bold; /* Bold header */
            color: #333; /* Darker text */
        }

        .tracked-card .list-group-item {
            font-size: 1rem; /* Normal text size */
            color: #555; /* Medium text color */
        }

        .view-btn {
            font-size: 0.9rem;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            text-decoration: none;
        }
        .view-btn:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .view-btn-delete {
            font-size: 0.9rem;
            color: #fff;
            background-color: #dc3545;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            text-decoration: none;
        }
        .view-btn-delete:hover {
            background-color: ##dc3570;
            color: #fff;
        }


    </style>
</head>
<body>
    <?php
        include('boilerplate.php');
    ?>
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
    <div class="container mt-4">
    <div class="row">
        <!-- Bills Tracked -->
        <div class="col-md-6">
            <div class="card tracked-card">
                <div class="card-header">
                    <h5>Bills Tracked</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php
                        try {
                            // Fetch tracked bills with bill details
                            $query = $db->prepare("
                                SELECT tb.bill_id, b.billNum, b.title
                                FROM tracked_bills tb
                                LEFT JOIN Bills b ON tb.bill_id = b.id
                                WHERE tb.user_id = :user_id
                            ");
                            $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                            $query->execute();
                            $bills = $query->fetchAll(PDO::FETCH_ASSOC);

                            if ($bills) {
                                foreach ($bills as $bill) {
                                    $bill_id = htmlspecialchars($bill['bill_id']);
                                    $billNum = htmlspecialchars($bill['billNum']);
                                    $title = htmlspecialchars($bill['title']);
                                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                    <span>$billNum: $title</span>
                                    <div>
                                        <a href='bill.php?id=$bill_id' class='view-btn'><i class='typcn typcn-eye-outline'> View</i></a>
                                        <form method='POST' style='display: inline;'>
                                            <input type='hidden' name='remove_bill_id' value='$bill_id'>
                                            <button type='submit' class='view-btn-delete' onclick='return confirm(Are you sure you want to remove this bill?)'><i class='typcn typcn-delete'>Remove</i></button>
                                        </form>
                                    </div>
                                  </li>";
                        }
                            } else {
                                echo "<li class='list-group-item text-muted'>No bills tracked yet.</li>";
                            }
                        } catch (PDOException $e) {
                            echo "<li class='list-group-item text-danger'>Error fetching bills: {$e->getMessage()}</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Legislators Tracked -->
        <div class="col-md-6">
            <div class="card tracked-card">
                <div class="card-header">
                    <h5>Legislators Tracked</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php
                        try {
                            // Fetch tracked legislators with legislator details
                            $query = $db->prepare("
                                SELECT tl.legislator_id, l.name, l.chamber
                                FROM tracked_legislators tl
                                LEFT JOIN legislators l ON tl.legislator_id = l.id
                                WHERE tl.user_id = :user_id
                            ");
                            $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                            $query->execute();
                            $legislators = $query->fetchAll(PDO::FETCH_ASSOC);

                            if ($legislators) {
                                foreach ($legislators as $legislator) {
                                    $legislator_id = htmlspecialchars($legislator['legislator_id']);
                                    $name = htmlspecialchars($legislator['name']);
                                    $chamber = htmlspecialchars($legislator['chamber']);
                                    echo "<li class='list-group-item'>
                                            <span>$name ($chamber)</span>
                                            <a href='profile.php?id=$legislator_id' class='view-btn'>View</a>
                                          </li>";
                                }
                            } else {
                                echo "<li class='list-group-item text-muted'>No legislators tracked yet.</li>";
                            }
                        } catch (PDOException $e) {
                            echo "<li class='list-group-item text-danger'>Error fetching legislators: {$e->getMessage()}</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card disclaimer-card">
        <div class="card-body">
            <h5 class="card-title">Disclaimer</h5>
            <p class="card-text">
            The information provided by the Legis360, a digital democracy platform, regarding Nigeria's National Assembly, is intended solely for general informational purposes. It is not produced, endorsed, or sanctioned by the government of Nigeria or any of its agencies.
            Legis360 operates independently, and the views and interpretations presented are entirely those of Legis360 and do not reflect any official government stance or policy. Any use of this information is entirely at your discretion.
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
