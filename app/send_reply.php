<?php
// Database connection
include 'db_connect.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: signin.php');
    exit;
}

// Get data from the form
$parent_id = $_POST['parent_id']; // ID of the original message
$receiver_id = $_POST['receiver_id']; // ID of the receiver (from the users table)
$subject = $_POST['subject'];
$message = $_POST['message'];
$sender_id = $_SESSION['id']; // Logged-in user's ID

// Prepare the query
$sql = "INSERT INTO messages (parent_id, sender_id, receiver_id, subject, message, created_at) 
        VALUES (:parent_id, :sender_id, :receiver_id, :subject, :message, NOW())";

// Prepare the statement
$stmt = $db->prepare($sql);

// Bind parameters
$stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
$stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
$stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
$stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
$stmt->bindParam(':message', $message, PDO::PARAM_STR);

// Execute the statement
if ($stmt->execute()) {
    echo "Reply sent successfully!";
    header("Location: view_message.php?id=" . $parent_id);
    exit;
} else {
    echo "Failed to send reply.";
}
?>

