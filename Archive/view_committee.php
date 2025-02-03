<?php
include 'header.php'; // Include your global header
include 'db_connect.php'; // Include your database connection
include 'committeeBillFetch.php'; // Include your database connection


// Fetch committee details based on the `id` passed in the URL
$committee_id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$committee_id) {
    die("Committee ID not provided.");
}

// Fetch committee details
$committeeQuery = $db->prepare("SELECT * FROM committees WHERE id = ?");
$committeeQuery->execute([$committee_id]);
$committee = $committeeQuery->fetch(PDO::FETCH_ASSOC);

// Ensure the member view is logged only once per session
if (!isset($_SESSION['viewed_committee'])) {
    $_SESSION['viewed_committee'] = []; // Initialize if not set
}

if (!in_array($committee_id, $_SESSION['viewed_committee'])) {
    logActivity($db, $_SESSION['id'], 'viewed_committee with ID: ' . $committee_id);
    $_SESSION['viewed_committee'][] = $committee_id; // Mark this member as viewed
}

// Fetch members of the committee
$membersQuery = $db->prepare("
    SELECT cm.legislator_id, l.name, l.position, l.state, l.constituency
    FROM committee_members cm
    JOIN legislators l ON cm.legislator_id = l.id
    WHERE cm.committee_id = ?
");
$membersQuery->execute([$committee_id]);
$members = $membersQuery->fetchAll(PDO::FETCH_ASSOC);

if (!$committee) {
    die("Committee not found.");
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($committee['name']); ?> - Committee Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="lad.css">
</head>
<body>
<div class="container mt-5">
    <!-- Committee Title -->
    <div class="mb-4 text-center">
        <h1 class="display-4"><?= htmlspecialchars($committee['name']); ?> </h1>
        <p class="lead text-muted"><?= htmlspecialchars($committee['chamber']); ?></p>
        <p><?= htmlspecialchars($committee['committee_function']); ?></p>
        <?php if ($user_role === 'admin'): ?>
            <a class="bttn" href="edit_committee.php?id=<?= urlencode($committee_id); ?>">Edit Committee</a>
        <?php endif; ?>
    </div>

    <!-- Members Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            Members of <?= htmlspecialchars($committee['name']); ?>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>State</th>
                    <th>Constituency</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($members as $index => $member): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= htmlspecialchars($member['name']); ?></td>
                        <td><?= htmlspecialchars($member['position']); ?></td>
                        <td><?= htmlspecialchars($member['state']); ?></td>
                        <td><?= htmlspecialchars($member['constituency']); ?></td>
                        <td>
                            <a href="profile.php?id=<?= $member['legislator_id']; ?>" 
                               class="btn btn-primary btn-sm">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Sponsored Bills -->
    <div class="profile-container table-responsive">
            <h5>Bills referred to <?= htmlspecialchars($committee['name']); ?> </h5>
            <table>
                <thead>
                    <tr>
                        <th>BN</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>First Reading</th>
                        <th>Second Reading</th>
                        <th>Third Reading</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        // Check if there are bills in the result set
                        if (!empty($bills)) {
                            // Loop through the bills and display each one
                            foreach ($bills as $bill) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($bill['billNum']); ?></td>
                                    <td><?= htmlspecialchars($bill['title']); ?></td>
                                    <td><?= htmlspecialchars($bill['billStatus']); ?></td>
                                    <td><?= htmlspecialchars($bill['firstReading'] ?? 'N/A' ); ?></td>
                                    <td><?= htmlspecialchars($bill['secondReading'] ?? 'N/A' ); ?></td>
                                    <td><?= htmlspecialchars($bill['thirdReading'] ?? 'N/A' ); ?></td>
                                    <td><a href="bill.php?id=<?= $bill['id'] ?>" class="btn btn-primary btn-sm">View</a></td>
                                </tr>
                            <?php } 
                        } else {
                            echo "<tr><td colspan='7'>No bill found for this committee.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
