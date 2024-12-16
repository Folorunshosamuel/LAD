<?php
// In your admin-only pages
session_start();
include 'db_connect.php';

$admin_ids = [10]; // List of user IDs with admin access, adjust as needed

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !in_array($_SESSION["id"], $admin_ids)) {
    header("location: signin.php");
    exit;
}

// Retrieve the message ID
$message_id = $_GET['id'];

// Fetch message details
$sql = "SELECT m.id, m.sender_id, m.receiver_id, m.subject, m.message, m.created_at, m.is_read, 
               CONCAT(u.fName, ' ', u.lName) AS sender_name, l.name AS receiver_name
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        JOIN legislators l ON m.receiver_id = l.id
        WHERE m.id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $message_id, PDO::PARAM_INT);
$stmt->execute();
$message = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="lad.css">
    <title>Message Details - Admin</title>
</head>
<body>

<h2>Message Details</h2>
<p><strong>From:</strong> <?= htmlspecialchars($message['sender_name']); ?></p>
<p><strong>To (Legislator):</strong> <?= htmlspecialchars($message['receiver_name']); ?></p>
<p><strong>Date:</strong> <?= htmlspecialchars($message['created_at']); ?></p>
<p><strong>Subject:</strong> <?= htmlspecialchars($message['subject']); ?></p>
<p><strong>Message:</strong></p>
<p><?= nl2br(htmlspecialchars($message['message'])); ?></p>

</body>
</html>
