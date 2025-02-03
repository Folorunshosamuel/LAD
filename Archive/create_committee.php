<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $committee_name = $_POST['committee_name'];
    $committee_function = $_POST['committee_function'];
    $chamber = $_POST['chamber'];
    $members = $_POST['members']; // Array of selected legislator IDs

    if (count($members) > 40) {
        echo "You can only add up to 40 members.";
        exit;
    }

    try {
        // Begin transaction
        $db->beginTransaction();

        // Insert into committees table
        $sql = "INSERT INTO committees (name, chamber, committee_function) VALUES (:name, :chamber, :function)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':name' => $committee_name,
            ':chamber' => $chamber,
            ':function' => $committee_function
        ]);

        // Get the last inserted committee ID
        $committee_id = $db->lastInsertId();

        // Insert members into committee_members table
        $sql = "INSERT INTO committee_members (committee_id, legislator_id) VALUES (:committee_id, :legislator_id)";
        $stmt = $db->prepare($sql);

        foreach ($members as $member_id) {
            $stmt->execute([
                ':committee_id' => $committee_id,
                ':legislator_id' => $member_id
            ]);
        }

        // Commit transaction
        $db->commit();
        echo "Committee created successfully!";
    } catch (PDOException $e) {
        // Rollback on error
        $db->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
// Initialize an empty message variable
$successMessage = '';
?>
