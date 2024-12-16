<?php
// Include database connection
include 'db_connect.php';

// Retrieve dashboard statistics
$totalMotionsQuery = $db->query("SELECT COUNT(*) as total FROM Motions");
$totalMotions = $totalMotionsQuery->fetch()['total'];

$MotionsByChamberQuery = $db->query("SELECT chamber, COUNT(*) as count FROM Motions GROUP BY chamber");
$MotionsByChamberResult = $MotionsByChamberQuery->fetchAll(PDO::FETCH_ASSOC);

$MotionsByChamber = [];
foreach ($MotionsByChamberResult as $row) {
    $MotionsByChamber[$row['chamber']] = $row['count'];
} 

$recentMotionsQuery = $db->query("SELECT id, title, chamber, committeeReferred, dateFiled FROM Motions ORDER BY dateFiled DESC LIMIT 5");
$recentMotions = $recentMotionsQuery->fetchAll();

$sponsorSummaryQuery = $db->query("SELECT sponsor_id, COUNT(*) as count, COALESCE(legislators.name) as sponsor_name
    FROM Motions LEFT JOIN legislators ON Motions.sponsor_id = legislators.id GROUP BY sponsor_id LIMIT 5
");

$sponsorSummary = $sponsorSummaryQuery->fetchAll(PDO::FETCH_ASSOC);

$allMotionsQuery = $db->query("SELECT Motions.id, Motions.title,  Motions.chamber, Motions.committeeReferred, Motions.dateFiled, COALESCE(legislators.name) AS sponsor_name
    FROM Motions LEFT JOIN legislators ON Motions.sponsor_id = legislators.id");

$allMotions = $allMotionsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motion Dashboard</title>
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
            <h2>Motions Overview</h2>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body card-summary">
                        <div>
                            <p class="mb-2 text-muted">Total Motions</p>
                            <p class="card-content"><?= htmlspecialchars($totalMotions) ?></p>
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
                            <p class="mb-2 text-muted">Senate Motions</p>
                            <p class="card-content"><?= htmlspecialchars($Motions = $MotionsByChamber['Senate'] ?? 0) ?></p>
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
                            <p class="mb-2 text-muted">House of Reps Motions</p>
                            <p class="card-content"><?= htmlspecialchars($horMotions = $MotionsByChamber['House of Reps'] ?? 0) ?></p>
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
                            <p class="mb-2 text-muted">Pending Motions</p>
                            <p class="card-content"><?= htmlspecialchars($pendingMotions = $MotionsByChamber['Pending'] ?? 0) ?></p>
                        </div>
                        <div class="icon-container bg-info">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Motions Table -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Recent Motions</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Chamber</th>
                            <th>Committee Reffered to</th>
                            <th>Date Filed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentMotions as $motion): ?>
                            <tr>
                                <td><a href="motion.php?id=<?= urlencode($motion['id']) ?>"><?= htmlspecialchars($motion['title']) ?></a></td>
                                <td><?= htmlspecialchars($motion['chamber']) ?></td>
                                <td><?= htmlspecialchars($motion['committeeReferred']) ?></td>
                                <td><?= htmlspecialchars($motion['dateFiled'] ?: 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
                            <th>Chamber</th>
                            <th>Bill Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sponsorSummary as $sponsor): ?>
                            <tr>
                                <td><?= htmlspecialchars($sponsor['sponsor_name'] ?: 'Unknown') ?></td>
                                <td><?= htmlspecialchars($motion['chamber']) ?></td>
                                <td><?= htmlspecialchars($sponsor['count']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- DataTable for All Motions -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">All Motions</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="allMotionsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Sponsor</th>
                                <th>Chamber</th>
                                <th>Committee</th>
                                <th>Date Filed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allMotions as $motion): ?>
                                <tr>
                                    <td><?= htmlspecialchars($motion['title']) ?></td>
                                    <td><?= htmlspecialchars($motion['sponsor_name'] ?? 'Unknown') ?></td>
                                    <td><?= htmlspecialchars($motion['chamber']) ?></td>
                                    <td><?= htmlspecialchars($motion['committeeReferred']) ?></td>
                                    <td><?= htmlspecialchars($motion['dateFiled']) ?></td>
                                    <td>
                                        <a href="motion.php?id=<?= urlencode($motion['id']) ?>" class="btn btn-primary btn-sm">View</a>
                                        <a href="edit-motion.php?id=<?= urlencode($motion['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
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
            $('#allMotionsTable').DataTable();
        });
    </script>
</body>
</html>
