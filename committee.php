<?php
include 'db_connect.php';

// Fetch all committees from the database
$query = "SELECT * FROM committees ORDER BY chamber, created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$committees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Committees of the National Assembly</title>
    <link rel="stylesheet" href="lad.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
    <?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
<div class="container">
    <h1>All Committees</h1>
    <table id="committeesTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Committee Name</th>
                <th>Chamber</th>
                <!-- <th>Created At</th> -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($committees as $committee): ?>
                <tr>
                    <td><?= htmlspecialchars($committee['name']); ?></td>
                    <!-- <td><?= htmlspecialchars($committee['committee_function']); ?></td> -->
                    <td><?= htmlspecialchars($committee['chamber']); ?></td>
                    <!-- <td><?= htmlspecialchars(date('d-M-Y', strtotime($committee['created_at']))); ?></td> -->
                    <td>
                        <a href="view_committee.php?id=<?= $committee['id']; ?>" class="btn btn-primary btn-sm">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#committeesTable').DataTable();
});
</script>
</body>
</html>
