<?php
// Include database connection and lad.css stylesheet
include 'db_connect.php';
echo '<link rel="stylesheet" href="lad.css">';

// Retrieve data for summary cards
$totalMembers = $db->query("SELECT COUNT(*) FROM horMembers")->fetchColumn();
$totalFemale = $db->query("SELECT COUNT(*) FROM horMembers WHERE gender = ' Female'")->fetchColumn();
$femalePercentage = ($totalMembers > 0) ? round(($totalFemale / $totalMembers) * 100, 2) : 0;
$totalMale = $db->query("SELECT COUNT(*) FROM horMembers WHERE gender = 'Male'")->fetchColumn();
$malePercentage = ($totalMembers > 0) ? round(($totalMale / $totalMembers) * 100, 2) : 0;
$totalYoungMembers = $db->query("SELECT COUNT(*) FROM horMembers WHERE TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 40")->fetchColumn();
$youngPercentage = ($totalMembers > 0) ? round(($totalYoungMembers / $totalMembers) * 100, 2) : 0;


// Retrieve recent bills for the activity card
$recentBills = $db->query("SELECT id, billNum, title, firstReading FROM Bills where chamber = 'House of reps' ORDER BY firstReading DESC LIMIT 5")->fetchAll();

// Retrieve all members for the members table
$members = $db->query("SELECT id, name, gender, dob, state, constituency, position FROM horMembers")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House of Representative</title>
    <link rel="stylesheet" href="lad.css"> <!-- Adjust path to your CSS file -->
    <style>
         .card-summary { display: flex; align-items: normal; justify-content: space-between; }
        .icon-container {
            width: 50px; height: 50px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; color: #fff; font-size: 1.5rem;
        }
        .note-red { color: red; }
        .activity-card, .members-table { margin-top: 20px; }
    </style>
</head>
<body>
    <?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>

    <div class="container-fluid">
        <h3 class="my-4">Nigeria House of Representatives - 10th Assembly</h3>

        <!-- Vector Map for State Analysis -->
        <div class="row">
            <div class="col-lg-12">
                <!-- <div id="nigeria-map" style="width: 100%; height: 400px;"></div> -->
                <div class="flourish-embed flourish-parliament" data-src="visualisation/20162114"><script src="https://public.flourish.studio/resources/embed.js"></script><noscript><img src="https://public.flourish.studio/visualisation/20162114/thumbnail" width="100%" alt="parliament visualization" /></noscript></div>
            </div>
        </div>

        <!-- Summary Cards new -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body card-summary">
                        <div>
                            <p class="mb-2 text-muted"><strong>Total Members</strong></p>
                            <h3 class="card-content"><?= htmlspecialchars($totalMembers) ?></h3>
                        </div>
                        <div class="icon-container bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body card-summary">
                        <div>
                            <p class="mb-2 text-muted"><strong>Females</strong></p>
                            <h3 class="card-content"><?= htmlspecialchars($totalFemale) ?></h3><span class="note-red">(<?= $femalePercentage ?>%)</span>
                            <p></p>
                        </div>
                        <div class="icon-container bg-success">
                            <i class="fas fa-female"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body card-summary">
                        <div>
                            <p class="mb-2 text-muted"><strong>Males</strong></p>
                            <h3 class="card-content"><?= htmlspecialchars($totalMale) ?></h3>
                            <p><span class="note-red">(<?= $malePercentage ?>%)</span></p>
                        </div>
                        <div class="icon-container bg-warning">
                            <i class="fas fa-male"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body card-summary">
                        <div>
                            <p class="mb-2 text-muted"><strong>Young Member</strong></p>
                            <h3 class="card-content"><?= htmlspecialchars($youngPercentage) ?>%<h3p>
                        </div>
                        <div class="icon-container bg-info">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- House of Reps Leadership -->
        <div class="card members-table">
            <div class="card-header bg-primary text-white">Principal Officers of the House of Representative</div>
            <div class="card-body">
            <?php
            // Include database connection and lad.css stylesheet
            include 'datacardhor.php';
            ?>
            </div>
        </div>
        
        <!-- Recent Bills Table with "View All Bills" Button -->
        <div class="row">
            <!-- Card 1 -->
            <div class="col-lg-6 col-md-6">
                <div class="card activity-card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between">
                        <span>Recent Bills in the House of Reps</span>
                        <a href="billdash.php" class="btn btn-light btn-sm">View All Bills</a>
                    </div>
                    <div class="card-body table-responsive">
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
                        Include 'memberPartyChart.php';
                        ?>
                       
                    </div>
                </div>
            </div>
        </div>
        <!-- Table of All Members with View Action -->
        <div class="card members-table">
            <div class="card-header bg-primary text-white">Homorable Members</div>
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
                                    <td>
                                        <a href="horprofile.php?id=<?= $member['id'] ?>" class="btn btn-primary btn-sm">View</a>
                                        <a href="horupdate.php?id=<?= urlencode($bill['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    </td>
                                    
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