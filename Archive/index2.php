<?php
// Include database connection and lad.css stylesheet
include 'db_connect.php';
echo '<link rel="stylesheet" href="lad.css">';

// Retrieve data for summary cards
$totalMembers = $db->query("SELECT COUNT(*) FROM legislators")->fetchColumn();
$totalSenate = $db->query("SELECT COUNT(*) FROM senateMembers")->fetchColumn();
$totalReps = $db->query("SELECT COUNT(*) FROM horMembers")->fetchColumn();
$totalFemale = $db->query("SELECT COUNT(*) FROM legislators WHERE gender = ' Female'")->fetchColumn();
$femalePercentage = ($totalMembers > 0) ? round(($totalFemale / $totalMembers) * 100, 2) : 0;
$totalMale = $db->query("SELECT COUNT(*) FROM legislators WHERE gender = 'Male'")->fetchColumn();
$malePercentage = ($totalMembers > 0) ? round(($totalMale / $totalMembers) * 100, 2) : 0;
$totalYoungMembers = $db->query("SELECT COUNT(*) FROM horMembers WHERE TIMESTAMPDIFF(YEAR, dob, CURDATE()) < 40")->fetchColumn();
$youngPercentage = ($totalMembers > 0) ? round(($totalYoungMembers / $totalMembers) * 100, 2) : 0;


// Retrieve recent bills for the activity card
$recentBills = $db->query("SELECT id, billNum, title, firstReading FROM Bills ORDER BY firstReading DESC LIMIT 5")->fetchAll();

// Retrieve all members for the members table
$members = $db->query("SELECT id, name, gender, dob, state, constituency, position FROM horMembers")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legislative Analysis dashboard - Welcome</title>
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
    <?php
    include 'boilerplate.php'; // Include the boilerplate after the header
    ?>

    <main>
    <div class="container">
            <section class="mb-5">
                <h6 class="text-primary mb-4">Need a quick admin panel</h6>
                <div class="row">
                    <div class="col-lg-6">
                        <h1 class="mb-3">Empowering Legislative Transparency.</h1>
                        <p class="text-muted">Track, Analyze, and Engage with Legislators like never befores.</p>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-sm-6 mb-5 d-flex align-items-center justify-content-center">
                                <span class="h1 mb-0 me-2 text-primary font-weight-bold">469 </span>
                                <span>Lawmakers</span>
                            </div>
                            <div class="col-sm-6 mb-5 d-flex align-items-center justify-content-center">
                                <span class="h1 mb-0 me-2 text-primary font-weight-bold">570 </span>
                                <span>BIlls</span>
                            </div>
                            <div class="col-sm-6 mb-5 d-flex align-items-center justify-content-center">
                                <span class="h1 mb-0 me-2 text-primary font-weight-bold">500+ </span>
                                <span>Users</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-5 mb-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-icon feature-icon-lg rounded border text-primary mb-40px">
                            <i class="typcn icon typcn-coffee tx-26"></i>
                        </div>
                        <h5 class="font-weight-bold">For Citizens</h5>
                        <p class="text-muted mb-20px">Stay informed with real-time updates on legislative activities. Access bills, motions, and petitions with just a click.</p>
                        <a href="#!" class="text-dark font-weight-bold">Learn more</a>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-icon feature-icon-lg rounded border text-primary mb-40px">
                            <i class="typcn icon typcn-briefcase tx-26"></i>
                        </div>
                        <h5 class="font-weight-bold">For Legislators</h5>
                        <p class="text-muted mb-20px">Your work made accessible. Showcase your activities, respond to messages, and connect with your constituents seamlessly.</p>
                        <a href="#!" class="text-dark font-weight-bold">Learn more</a>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-icon feature-icon-lg rounded border text-primary mb-40px">
                            <i class="typcn icon typcn-folder-add tx-26"></i>
                        </div>
                        <h5 class="font-weight-bold">Data Insights</h5>
                        <p class="text-muted mb-20px">Analyze legislative data for smarter decisions. Visualize trends, gender representation, and party distribution at a glance.</p>
                        <a href="#!" class="text-dark font-weight-bold">Learn more</a>
                    </div>
                </div>
            </section>
        </div> 
    </main>
    <div class="container-fluid">
        <!-- Summary Cards new -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body card-summary">
                        <div>
                            <p class="mb-2 text-muted"><strong>Total Senators</strong></p>
                            <h3 class="card-content"><?= htmlspecialchars($totalSenate) ?></h3>
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
                            <p class="mb-2 text-muted"><strong>Total Reps</strong></p>
                            <h3 class="card-content"><?= htmlspecialchars($totalReps) ?></h3>
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
        </div>

        <!-- House of Reps Leadership -->
        <div class="card members-table">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between">
                        <span>Principal Officers of the National Assembly</span>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            View Members
                            </button>
                            <div class="dropdown-menu tx-13" aria-labelledby="dropdownMenuButton" style="">
                            <a class="dropdown-item" href="sendash.php">Senate</a>
                            <a class="dropdown-item" href="hordash.php">House of Reps</a>
                            </div>
                        </div>
                    </div>
            <div class="card-body">
            <?php
            include 'datacardsen.php';
            ?>
            <?php
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
                        <span>Recent Bills in the National Assembly</span>
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
                        <a href="all-senate-bills.php" class="btn btn-light btn-sm">View All Senate Bills</a>
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
                                        <a href="horupdate.php?id=<?= urlencode($member['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    </td>
                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
    include 'footer.php';
    ?>

    <!-- Scripts -->
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
    <script>
      $(function(){
        'use strict'

      });
    </script> 
</body>
</html>
