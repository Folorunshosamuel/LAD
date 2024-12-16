<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'root', 'lad');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'type' and 'id' are set in the URL
// if (isset($_GET['type']) && isset($_GET['id'])) {
//     $table = $_GET['type'] === 'senate' ? 'senateMembers' : 'horMembers';
//     $id = intval($_GET['id']);
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the member’s data
    $query = "SELECT * FROM legislators where id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    // Fetch bills sponsored by the member
    $billQuery = "SELECT * FROM Bills WHERE sponsor_id = ?";
    $billStmt = $conn->prepare($billQuery);
    $billStmt->bind_param("i", $id);
    $billStmt->execute();
    $bills = $billStmt->get_result();
} else {
    die("Member type or ID is not defined in the URL.");
}
?>