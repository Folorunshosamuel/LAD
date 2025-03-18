<?php
include 'header.php'; // Include your global header
include 'db_connect.php';

$committee_id = $_GET['id'] ?? null;

// Fetch committee details
$committee = null;
if ($committee_id) {
    $stmt = $db->prepare("SELECT * FROM committees WHERE id = ?");
    $stmt->execute([$committee_id]);
    $committee = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$committee) {
    die("Committee not found.");
}

// Fetch existing members of the committee
$stmt = $db->prepare("
    SELECT cm.id as cm_id, l.id as legislator_id, l.name, l.chamber, l.position
    FROM committee_members cm
    JOIN legislators l ON cm.legislator_id = l.id
    WHERE cm.committee_id = ?
");
$stmt->execute([$committee_id]);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Handle adding new members
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['add_members'])) {
        $newMembers = $_POST['add_members']; // Array of legislator IDs
        $addStmt = $db->prepare("INSERT INTO committee_members (committee_id, legislator_id) VALUES (?, ?)");

        foreach ($newMembers as $legislator_id) {
            $addStmt->execute([$committee_id, $legislator_id]);
        }

       /*  header("Location: edit_committee.php?id=" . $committee_id);
        exit; */
    }

    // Handle member removal
    if (isset($_POST['remove_member_id'])) {
        $removeMemberId = $_POST['remove_member_id'];
        $removeStmt = $db->prepare("DELETE FROM committee_members WHERE id = ?");
        $removeStmt->execute([$removeMemberId]);

        /* header("Location: edit_committee.php?id=" . $committee_id);
        exit; */
        
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
    <div class="container">
        <h1>Edit Committee</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="committee_name">Committee Name:</label>
                <input type="text" id="committee_name" name="committee_name" value="<?= htmlspecialchars($committee['name']); ?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="committee_function">Committee Function:</label>
                <textarea id="committee_function" name="committee_function" class="form-control" readonly><?= htmlspecialchars($committee['committee_function']); ?></textarea>
            </div>

            <h2>Current Members</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Chamber</th>
                        <th>Position</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td><?= htmlspecialchars($member['name']); ?></td>
                            <td><?= htmlspecialchars($member['chamber']); ?></td>
                            <td><?= htmlspecialchars($member['position']); ?></td>
                            <td>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="remove_member_id" value="<?= $member['cm_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2>Add New Members</h2>
            <div class="form-group">
                <label for="add_members">Select Members:</label>
                <select id="add_members" name="add_members[]" class="form-control select2" multiple="multiple">
                    <!-- Members will be dynamically populated -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Members</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2 for searchable dropdown
            $('#add_members').select2({
                placeholder: "Choose members",
                width: '100%'
            });

            // Dynamically load members based on chamber selection
            const chamber = '<?= htmlspecialchars($committee['chamber']); ?>';
            $.ajax({
                url: 'fetch_legislators.php',
                type: 'GET',
                data: { chamber },
                success: function (data) {
                    const members = JSON.parse(data);

                    if (members.error) {
                        alert(members.error);
                        return;
                    }

                    // Populate the dropdown
                    members.forEach(member => {
                        $('#add_members').append(new Option(member.name, member.id));
                    });
                },
                error: function () {
                    alert('Failed to fetch legislators. Please try again.');
                }
            });
        });
    </script>
</body>
</html>
