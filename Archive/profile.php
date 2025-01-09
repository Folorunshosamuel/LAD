<?php
// Include database connection and lad.css stylesheet
include 'header.php';
include 'memberfetch.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($member['name']); ?> - Profile</title>
</head>
<body>
    <div class="container">
        <!-- Profile Section -->
        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-image">
                    <?php if (!empty($member['image'])): ?>
                        <img src="<?= htmlspecialchars($member['image']); ?>" alt="Profile Image">
                    <?php else: ?>
                        <img src="uploads/avatar.webp" alt="Default Profile Image">
                    <?php endif; ?>
                </div>
                <div class="profile-details">
                    <h2><?= htmlspecialchars($member['name']); ?></h2>
                    <p><strong>State:</strong> <?= htmlspecialchars($member['state']); ?> | <strong>Constituency:</strong> <?= htmlspecialchars($member['constituency']); ?></p>
                    <p><strong>Chamber:</strong> <?= htmlspecialchars($member['chamber']); ?></p>
                    <p><strong>Position:</strong> <?= htmlspecialchars($member['position']); ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($member['gender']); ?></p>
                    <p><strong>DOB:</strong> <?= htmlspecialchars($member['dob'] ?? 'N/A'); ?></p>
                    <p><strong>Party:</strong> <?= htmlspecialchars($member['party'] ?? 'N/A'); ?></p>
                    <p><strong>Constituency Address:</strong> <?= htmlspecialchars($member['cAddress'] ?? 'N/A'); ?></p>
                    <h5>Biography</h5>
                    <p><?php echo htmlspecialchars($member['biography'] ?? "No biography available."); ?></p>
                    <?php if ($user_role === 'admin'): ?>
                        <a class="bttn" href="profile_update.php?id=<?= urlencode($id); ?>">Edit Profile</a>
                    <?php endif; ?>
                    <a class="bttn" href="message.php?id=<?= urlencode($id); ?>">Send a message</a>
                </div>
            </div>
        </div>

        <!-- Recent Bills Table with "View All Bills" Button -->
        <div class="row">
            <!-- Card 1 -->
            <div class="col-lg-6 col-md-6">
                <div class="card activity-card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between">
                        <span>Sponsored Bill(s)</span>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="recentBillsTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>BN</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                if ($bills->num_rows > 0) {
                                    while ($bill = $bills->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($bill['billNum']); ?></td>
                                    <td><?= htmlspecialchars($bill['title']); ?></td>
                                    <td><?= htmlspecialchars($bill['billStatus']); ?></td>
                                    <td><a href="bill.php?id=<?= $bill['id'] ?>" class="btn btn-primary btn-sm">View</a></td>
                                </tr>
                            <?php } 
                            } else {
                                echo "<tr><td colspan='7'>No bill found for this legislator.</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card 2 (Add as many cards as needed for side-by-side layout) -->
            <div class="col-lg-6 col-md-6">
                <div class="card activity-card">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between">
                        <span>Predecessor(s)</span>
                        <!-- <a href="all-senate-bills.php" class="btn btn-light btn-sm">View All Senate Bills</a> -->
                    </div>
                    <div class="card-body table-responsive">
                        <table id="recentBillsTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Party</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($predecessors->num_rows > 0) {
                                while ($predecessor = $predecessors->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($predecessor['name']); ?></td>
                                        <td><?= htmlspecialchars($predecessor['party']); ?></td>
                                        <td><a href="profile.php?id=<?= htmlspecialchars($predecessor['id']); ?>" class="btn btn-primary btn-sm">View</a></td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='6'>No predecessors found for this legislator.</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
