<?php
include 'db_connect.php';

// Get the chamber from the AJAX request
$chamber = $_GET['chamber'] ?? '';

if ($chamber) {
    try {
        // Fetch legislators based on the selected chamber
        $sql = "SELECT id, name FROM legislators WHERE chamber = :chamber";
        $stmt = $db->prepare($sql);
        $stmt->execute([':chamber' => $chamber]);
        $legislators = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return JSON response
        echo json_encode($legislators);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Chamber not specified.']);
}
?>
