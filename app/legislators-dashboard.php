<?php
// Include database connection
include 'db_connect.php';

// Retrieve totals and percentages for summary cards
$totalMembersQuery = $db->query("SELECT COUNT(*) as total FROM senateMembers");
$totalMembers = $totalMembersQuery->fetchColumn();

$totalFemaleQuery = $db->query("SELECT COUNT(*) as female_total FROM senateMembers WHERE gender = 'Female'");
$totalFemale = $totalFemaleQuery->fetchColumn();
$femalePercentage = ($totalMembers > 0) ? round(($totalFemale / $totalMembers) * 100, 2) : 0;

$totalMaleQuery = $db->query("SELECT COUNT(*) as male_total FROM senateMembers WHERE gender = 'Male'");
$totalMale = $totalMaleQuery->fetchColumn();
$malePercentage = ($totalMembers > 0) ? round(($totalMale / $totalMembers) * 100, 2) : 0;

// Calculate percentage of young members (e.g., age < 40)
$youngMembersQuery = $db->query("SELECT COUNT(*) as young_total FROM senateMembers WHERE dob < 40");
$totalYoungMembers = $youngMembersQuery->fetchColumn();
$youngPercentage = ($totalMembers > 0) ? round(($totalYoungMembers / $totalMembers) * 100, 2) : 0;

// Retrieve recent bills for the activity card
$recentBillsQuery = $db->query("SELECT title, firstReading FROM Bills ORDER BY firstReading DESC LIMIT 5");
$recentBills = $recentBillsQuery->fetchAll();

// Retrieve all members for the members table
$membersQuery = $db->query("SELECT name, gender, dob, state, position FROM senateMembers");
$members = $membersQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Legislators Dashboard</title>
    <link rel="stylesheet" href="path/to/lad.css"> <!-- Ensure this path is correct -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jqvmap.min.css">
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

        <!-- Summary Cards with Consistent Style -->
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Members</h5>
                        <p class="card-text"><?= $totalMembers ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Female Members</h5>
                        <p class="card-text"><?= $totalFemale ?> <span class="note-red">(<?= $femalePercentage ?>%)</span></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Male Members</h5>
                        <p class="card-text"><?= $totalMale ?> <span class="note-red">(<?= $malePercentage ?>%)</span></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Percentage of Young Members</h5>
                        <p class="card-text"><?= $youngPercentage ?>%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Card (Recent Bills) -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Recent Bills</div>
            <div class="card-body">
                <ul>
                    <?php foreach ($recentBills as $bill): ?>
                        <li><?= htmlspecialchars($bill['title']) ?> - <small><?= htmlspecialchars($bill['firstReading']) ?></small></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Table of All Members -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">All Members</div>
            <div class="card-body">
                <table id="membersTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Age</th>
                            <th>State</th>
                            <th>Position</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?= htmlspecialchars($member['name']) ?></td>
                                <td><?= htmlspecialchars($member['gender']) ?></td>
                                <td><?= htmlspecialchars($member['dob']) ?></td>
                                <td><?= htmlspecialchars($member['state']) ?></td>
                                <td><?= htmlspecialchars($member['position']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.nigeria.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#membersTable').DataTable();

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
