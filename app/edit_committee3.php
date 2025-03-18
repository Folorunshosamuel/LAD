<?php
include 'db_connect.php';

$successMessage = '';
$errorMessage = '';
$committee_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$committee_id) {
    die("Committee ID not provided.");
}

// Fetch existing committee details
$committeeQuery = $db->prepare("SELECT * FROM committees WHERE id = :id");
$committeeQuery->execute([':id' => $committee_id]);
$committee = $committeeQuery->fetch(PDO::FETCH_ASSOC);

if (!$committee) {
    die("Committee not found.");
}

// Fetch existing members
$memberQuery = $db->prepare("SELECT legislator_id FROM committee_members WHERE committee_id = :committee_id");
$memberQuery->execute([':committee_id' => $committee_id]);
$selectedMembers = $memberQuery->fetchAll(PDO::FETCH_COLUMN);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $committee_name = $_POST['name'];
    $committee_function = $_POST['committee_function'];
    $chamber = $_POST['chamber'];
    $members = $_POST['members'];

    if (count($members) > 40) {
        $errorMessage = "You can only add up to 40 members.";
    } else {
        try {
            $db->beginTransaction();

            // Update committee details
            $updateQuery = $db->prepare("UPDATE committees SET name = :name, chamber = :chamber, committee_function = :function WHERE id = :id");
            $updateQuery->execute([
                ':name' => $committee_name,
                ':chamber' => $chamber,
                ':function' => $committee_function,
                ':id' => $committee_id
            ]);

            // Remove existing members
            $deleteMembersQuery = $db->prepare("DELETE FROM committee_members WHERE committee_id = :committee_id");
            $deleteMembersQuery->execute([':committee_id' => $committee_id]);

            // Add updated members
            $insertMembersQuery = $db->prepare("INSERT INTO committee_members (committee_id, legislator_id) VALUES (:committee_id, :legislator_id)");
            foreach ($members as $member_id) {
                $insertMembersQuery->execute([
                    ':committee_id' => $committee_id,
                    ':legislator_id' => $member_id
                ]);
            }

            $db->commit();
            $successMessage = "Committee updated successfully.";
        } catch (PDOException $e) {
            $db->rollBack();
            $errorMessage = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Committee</title>
    <link rel="stylesheet" href="lad.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        <h1>Edit Committee</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="committee_name">Committee Name:</label>
                <input type="text" id="committee_name" name="committee_name" class="form-control" value="<?= htmlspecialchars($committee['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="committee_function">Committee Function:</label>
                <textarea id="committee_function" name="committee_function" class="form-control" required><?= htmlspecialchars($committee['committee_function']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="chamber">Chamber:</label>
                <select id="chamber" name="chamber" class="form-control" required>
                    <option value="">Select Chamber</option>
                    <option value="Senate" <?= $committee['chamber'] === 'Senate' ? 'selected' : '' ?>>Senate</option>
                    <option value="House of Reps" <?= $committee['chamber'] === 'House of Reps' ? 'selected' : '' ?>>House of Reps</option>
                </select>
            </div>
            <div class="form-group">
                <label for="members">Select Members:</label>
                <select id="members" name="members[]" class="form-control select2" multiple="multiple" required>
                    <!-- Members will be dynamically populated here -->
                </select>
                <small>Select up to 40 members.</small>
            </div>
            <button type="submit" class="btn btn-primary">Update Committee</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
    function loadMembers(chamber, selectedMembers = []) {
    $('#members').empty(); // Clear the select box

    if (chamber) {
        $.ajax({
            url: 'fetch_legislators.php',
            type: 'GET',
            data: { chamber },
            success: function (data) {
                try {
                    const members = JSON.parse(data);

                    if (members.error) {
                        alert(members.error);
                        return;
                    }

                    members.forEach(member => {
                        const isSelected = selectedMembers.includes(String(member.id));
                        const option = new Option(member.name, member.id, isSelected, isSelected);
                        $('#members').append(option);
                    });
                } catch (e) {
                    console.error("Invalid JSON response:", data);
                    alert("Failed to parse legislators. Please check the response format.");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
                alert('Failed to fetch legislators. Please try again.');
            }
        });
    }
}
$(document).ready(function () {
    $('#members').select2({
        placeholder: "Choose members",
        width: '100%'
    });

    function loadInitialMembers() {
        const chamber = $('#chamber').val();
        const selectedMembers = <?= json_encode($selectedMembers); ?>;
        loadMembers(chamber, selectedMembers);
    }

    $('#chamber').on('change', function () {
        const chamber = $(this).val();
        loadMembers(chamber);
    });

    // Load members for the selected chamber on page load
    loadInitialMembers();
});
</script>
</body>
</html>
