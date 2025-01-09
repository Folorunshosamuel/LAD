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

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the member’s data
    $query = "SELECT * FROM legislators where id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    if (!$member) {
        die("Member not found.");
    }
    
    // Ensure the member view is logged only once per session
    if (!isset($_SESSION['viewed_member'])) {
        $_SESSION['viewed_member'] = []; // Initialize if not set
    }
    
    if (!in_array($id, $_SESSION['viewed_member'])) {
        logActivity($db, $_SESSION['id'], 'Viewed Member with ID: ' . $id);
        $_SESSION['viewed_member'][] = $id; // Mark this member as viewed
    }

    // Fetch the predecessor's data
    $predecessorQuery = $conn->prepare("SELECT * FROM legislators WHERE id = ?");
    $predecessorQuery->bind_param("i", $member['predecessor_id']);
    $predecessorQuery->execute();
    $predecessors = $predecessorQuery->get_result();

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