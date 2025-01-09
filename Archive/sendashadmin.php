<?php
// Include database connection and lad.css stylesheet
include 'db_connect.php';
echo '<link rel="stylesheet" href="lad.css">';

// Retrieve data for summary cards
$totalMembers = $db->query("SELECT COUNT(*) FROM senateMembers")->fetchColumn();
$totalFemale = $db->query("SELECT COUNT(*) FROM senateMembers WHERE gender = 'Female'")->fetchColumn();
$femalePercentage = ($totalMembers > 0) ? round(($totalFemale / $totalMembers) * 100, 2) : 0;
$totalMale = $db->query("SELECT COUNT(*) FROM senateMembers WHERE gender = 'Male'")->fetchColumn();
$malePercentage = ($totalMembers > 0) ? round(($totalMale / $totalMembers) * 100, 2) : 0;
$totalYoungMembers = $db->query("SELECT COUNT(*) FROM senateMembers WHERE TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 60")->fetchColumn();
$youngPercentage = ($totalMembers > 0) ? round(($totalYoungMembers / $totalMembers) * 100, 2) : 0;


// Retrieve recent bills for the activity card
$recentBills = $db->query("SELECT id, billNum, title, firstReading FROM Bills where chamber = 'Senate' ORDER BY firstReading DESC LIMIT 5")->fetchAll();

// Retrieve all members for the members table
$members = $db->query("SELECT id, name, gender, dob, state, constituency, position FROM senateMembers")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House of Representative</title>
    <link rel="stylesheet" href="lad.css"> <!-- Adjust path to your CSS file -->
    <style>
        .summary-card { padding: 20px; text-align: center; }
        .note-red { color: red; }
        .activity-card, .members-table { margin-top: 20px; }
    </style>
</head>
<body>
    <?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>

    <div class="container-fluid">
        <h2 class="my-4">Legislators Dashboard</h2>

        <!-- Vector Map for State Analysis -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div id="nigeria-map" style="width: 100%; height: 400px;"></div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="card summary-card bg-primary text-white">
                    <h4>Total Members</h4>
                    <p><?= $totalMembers ?></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card summary-card bg-success text-white">
                    <h4>Total Female</h4>
                    <p><?= $totalFemale ?> <span class="note-red">(<?= $femalePercentage ?>%)</span></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card summary-card bg-info text-white">
                    <h4>Total Male</h4>
                    <p><?= $totalMale ?> <span class="note-red">(<?= $malePercentage ?>%)</span></p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card summary-card bg-warning text-white">
                    <h4>Percentage of Young Members</h4>
                    <p><?= $youngPercentage ?>%</p>
                </div>
            </div>
        </div>

        <!-- House of Reps Leadership -->
        <div class="card members-table">
            <div class="card-header bg-primary text-white">Principal Officers of the Senate</div>
            <div class="card-body">
            <?php
            // Include database connection and lad.css stylesheet
            include 'datacardsen.php';
            ?>
            </div>
        </div>
        
        <!-- Recent Bills Table with "View All Bills" Button -->
        <div class="row">
            <!-- Card 1 -->
            <div class="col-lg-6 col-md-6">
                <div class="card activity-card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between">
                        <span>Recent Bills in the Senate</span>
                        <a href="billdash.php" class="btn btn-light btn-sm">View All Bills</a>
                    </div>
                    <div class="card-body">
                        <table id="recentBillsTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>BN</th>
                                    <th>Title</th>
                                    <th>Date Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentBills as $bill): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($bill['billNum']) ?></td>
                                        <td><?= htmlspecialchars($bill['title']) ?></td>
                                        <td><?= htmlspecialchars($bill['firstReading']) ?></td>
                                        <td><a href="bill.php?id=<?= $bill['id'] ?>" class="btn btn-primary btn-sm">View</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card 2 (Add as many cards as needed for side-by-side layout) -->
            <div class="col-lg-6 col-md-6">
                <div class="card activity-card">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between">
                        <span>Representative by Political Party</span>
                        <!-- <a href="all-senate-bills.php" class="btn btn-light btn-sm">View All Senate Bills</a> -->
                    </div>
                    <div class="card-body">
                        <?php
                        Include 'memberPartyChartsen.php';
                        ?>
                       
                    </div>
                </div>
            </div>
        </div>
        <!-- Table of All Members with View Action -->
        <div class="card members-table">
            <div class="card-header bg-primary text-white">Senate Members</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="membersTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>State</th>
                                <th>Constituency</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $member): ?>
                                <tr>
                                    <td><?= htmlspecialchars($member['name']) ?></td>
                                    <td><?= htmlspecialchars($member['gender']) ?></td>
                                    <td><?= htmlspecialchars($member['dob'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($member['state']) ?></td>
                                    <td><?= htmlspecialchars($member['constituency']) ?></td>
                                    <td><?= htmlspecialchars($member['position']) ?></td>
                                    <td><a href="horprofile.php?id=<?= $member['id'] ?>" class="btn btn-primary btn-sm">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#membersTable').DataTable();
        });
    </script>
    <script>
            // Initialize Nigeria Map
            $('#nigeria-map').vectorMap({
                map: 'nigeria_en',
                backgroundColor: '#f4f4f4',
                color: '#333333',
                hoverOpacity: 0.7,
                selectedColor: '#666666',
                enableZoom: true,
                showTooltip: true,
                values: <?php /* Insert state-specific data here as a JavaScript object */ ?>,
                scaleColors: ['#C8EEFF', '#006491'],
                normalizeFunction: 'polynomial'
            });
        });
    </script>
</body>
</html>
