<?php
session_start();
include 'db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: signin.php");
    exit;
}

// Retrieve form data
$sender_id = $_SESSION["id"];  // ID of the logged-in user
$receiver_id = $_POST['receiver_id'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Verify that receiver exists in legislators table
$stmt = $db->prepare("SELECT COUNT(*) FROM legislators WHERE id = :receiver_id");
$stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
$stmt->execute();
$receiver_exists = $stmt->fetchColumn();

if (!$receiver_exists) {
    die("Error: The specified legislator does not exist.");
}

// Insert message into the database
$sql = "INSERT INTO messages (sender_id, receiver_id, subject, message) VALUES (:sender_id, :receiver_id, :subject, :message)";
$stmt = $db->prepare($sql);
$stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
$stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
$stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
$stmt->bindParam(':message', $message, PDO::PARAM_STR);

if ($stmt->execute()) {
    echo "Message sent successfully!";
} else {
    echo "Failed to send message. Please try again.";
}
?>
