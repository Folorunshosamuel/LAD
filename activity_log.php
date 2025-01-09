<?php
include 'db_connect.php';

    $sql = 'SELECT a.*, CONCAT(u.fName, " ", u.lName) AS user_name 
            FROM activity_log a 
            JOIN users u ON a.user_id = u.id 
            ORDER BY a.created_at DESC';
    $stmt = $db->query($sql);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Activity Log</title>
    <link rel="stylesheet" href="lad.css">
</head>
<body>
    <h1>Activity Log</h1>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>IP Address</th>
                <th>User Agent</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['user_name']); ?></td>
                    <td><?= htmlspecialchars($log['action']); ?></td>
                    <td><?= htmlspecialchars($log['ip_address']); ?></td>
                    <td><?= htmlspecialchars($log['user_agent']); ?></td>
                    <td><?= htmlspecialchars($log['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
