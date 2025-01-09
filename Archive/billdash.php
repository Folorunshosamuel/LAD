<?php
// Include database connection
include 'db_connect.php';

// Retrieve dashboard statistics
$totalBillsQuery = $db->query("SELECT COUNT(*) as total FROM Bills");
$totalBills = $totalBillsQuery->fetch()['total'];

$billsByStatusQuery = $db->query("SELECT billStatus, COUNT(*) as count FROM Bills GROUP BY billStatus");
$billsByStatusResult = $billsByStatusQuery->fetchAll(PDO::FETCH_ASSOC);

$billsByStatus = [];
foreach ($billsByStatusResult as $row) {
    $billsByStatus[$row['billStatus']] = $row['count'];
}

$recentBillsQuery = $db->query("SELECT id, billNum, title, billStatus, firstReading FROM Bills ORDER BY firstReading DESC LIMIT 5");
$recentBills = $recentBillsQuery->fetchAll();

$sponsorSummaryQuery = $db->query("SELECT sponsor_id, COUNT(*) as count, (SELECT name FROM legislators WHERE id = sponsor_id) as sponsor_name FROM Bills GROUP BY sponsor_id LIMIT 5");
$sponsorSummary = $sponsorSummaryQuery->fetchAll();

$allBillsQuery = $db->query("SELECT Bills.id, Bills.billNum, Bills.title, Bills.billStatus, Bills.firstReading, legislators.name AS sponsor_name
                             FROM Bills LEFT JOIN legislators ON Bills.sponsor_id = legislators.id"); // This covers for both chambers as we are calling the legislators table directly
$allBills = $allBillsQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .card-summary { display: flex; align-items: center; justify-content: space-between; }
        .icon-container {
            width: 50px; height: 50px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; color: #fff; font-size: 1.5rem;
        }
        .bg-primary { background-color: #5b47fb !important; }
        .bg-success { background-color: #3bbc8a !important; }
        .bg-warning { background-color: #ffbb33 !important; }
        .bg-info { background-color: #17a2b8 !important; }
        .card-content p { margin: 0; font-size: 1.2rem; font-weight: 500; }
    </style>
</head>
<body>
    <?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
    <div class="container-fluid px-2">
        <div class="az-content-label mg-b-5">
            <h2>Senate Bills Overview</h2>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body card-summary">
                        <div>
                            <p class="mb-2 text-muted"><strong>Total Bills</strong></p>
                            <h3 class="card-content"><?= htmlspecialchars($totalBills) ?></h3>
                        </div>
                        <div class="icon-container bg-primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body card-summary">
                        <div>
                            <p class="mb-2 text-muted"><strong>Passed Bills</strong></p>
                            <h3 class="card-content"><?= htmlspecialchars($passedBills = $billsByStatus['Passed'] ?? 0) ?></h3>
                        </div>
                        <div class="icon-container bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body card-summary">
                        <div>
                            <p class="mb-2 text-muted"><strong>Rejected Bills</strong></p>
                            <h3 class="card-content"><?= htmlspecialchars($rejectedBills = $billsByStatus['Rejected'] ?? 0) ?></h3>
                        </div>
                        <div class="icon-container bg-warning">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body card-summary">
                        <div>
                            <p class="mb-2 text-muted"><strong>Pending Bills</strong></p>
                            <h3 class="card-content"><?= htmlspecialchars($pendingBills = $billsByStatus['Pending'] ?? 0) ?><h3p>
                        </div>
                        <div class="icon-container bg-info">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bills Table -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Recent Bills</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>BN</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>First Reading</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentBills as $bill): ?>
                            <tr>
                                <td><a href="bill.php?id=<?= urlencode($bill['id']) ?>"><?= htmlspecialchars($bill['billNum']) ?></a></td>
                                <td><a href="bill.php?id=<?= urlencode($bill['id']) ?>"><?= htmlspecialchars($bill['title']) ?></a></td>
                                <td><?= htmlspecialchars($bill['billStatus']) ?></td>
                                <td><?= htmlspecialchars($bill['firstReading'] ?: 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Bill Progression chart -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Bill Progression chart</div>
            <div class="card-body p-0">
            <?php
                // Include database connection
                include 'bill-progression.php';
                ?>
            </div>
        </div>

        <!-- Sponsor Summary Table -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Top Sponsors</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Sponsor</th>
                            <th>Bill Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sponsorSummary as $sponsor): ?>
                            <tr>
                                <td><?= htmlspecialchars($sponsor['sponsor_name'] ?: 'Unknown') ?></td>
                                <td><?= htmlspecialchars($sponsor['count']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- DataTable for All Bills -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">All Bills</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="allBillsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>BN</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Sponsor</th>
                                <th>First Reading</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allBills as $bill): ?>
                                <tr>
                                    <td><?= htmlspecialchars($bill['billNum']) ?></td>
                                    <td><?= htmlspecialchars($bill['title']) ?></td>
                                    <td><?= htmlspecialchars($bill['billStatus']) ?></td>
                                    <td><?= htmlspecialchars($bill['sponsor_name'] ?? 'Unknown') ?></td>
                                    <td><?= htmlspecialchars($bill['firstReading'] ?: 'N/A') ?></td>
                                    <td>
                                        <a href="bill.php?id=<?= urlencode($bill['id']) ?>" class="btn btn-primary btn-sm">View</a>
                                        <a href="edit-bill.php?id=<?= urlencode($bill['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#allBillsTable').DataTable();
        });
    </script>
</body>
</html>
