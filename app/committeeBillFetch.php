<?php

// Assume the committee ID is passed to this script (e.g., via URL or form)
$committee_id = $_GET['id']; // Ensure this is sanitized properly

// Prepare SQL to fetch bills referred to a specific committee
$billQuery = "SELECT * FROM Bills WHERE committeeReferred = :committee_id";
$billStmt = $db->prepare($billQuery);

// Bind the committee_id using PDO's bindValue() method
$billStmt->bindValue(':committee_id', $committee_id, PDO::PARAM_INT);

// Execute the query
$billStmt->execute();

// Get the results
$bills = $billStmt->fetchAll(PDO::FETCH_ASSOC);

/* // Check if there are bills referred to the committee
if ($bills) {
    // Loop through the result set and display bills
    foreach ($bills as $bill) {
        echo "<p><strong>Bill Title:</strong> " . htmlspecialchars($bill['title']) . "</p>";
        echo "<p><strong>Bill Number:</strong> " . htmlspecialchars($bill['billNum']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($bill['billStatus']) . "</p>";
        echo "<hr>";
    }
} else {
    echo "<p>No bills have been referred to this committee.</p>";
}
 */
?>