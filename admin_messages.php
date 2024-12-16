<?php
// In your admin-only pages
session_start();
include 'db_connect.php';

$admin_ids = [10]; // List of user IDs with admin access, adjust as needed

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["id"], $admin_ids)) {
    header("location: signin.php");
    exit;
}
// Fetch all messages, optionally filtered by legislator or sender
$sql = "SELECT m.id, m.sender_id, m.receiver_id, m.subject, m.message, m.created_at, m.is_read, 
               CONCAT(u.fName, ' ', u.lName) AS sender_name, l.name AS receiver_name
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        JOIN legislators l ON m.receiver_id = l.id
        ORDER BY m.created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="lad.css">
    <title>Admin Message Dashboard</title>
</head>
<body>

<h2>Admin - All Messages</h2>
<table class="table">
    <thead>
        <tr>
            <th>From</th>
            <th>To (Legislator)</th>
            <th>Subject</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($messages as $msg): ?>
            <tr>
                <td><?= htmlspecialchars($msg['sender_name']); ?></td>
                <td><?= htmlspecialchars($msg['receiver_name']); ?></td>
                <td><?= htmlspecialchars($msg['subject']); ?></td>
                <td><?= htmlspecialchars($msg['created_at']); ?></td>
                <td><?= $msg['is_read'] ? 'Read' : 'Unread'; ?></td>
                <td><a href="admin_view_message.php?id=<?= $msg['id']; ?>">View</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
