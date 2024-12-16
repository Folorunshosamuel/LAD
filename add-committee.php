<?php
include 'db_connect.php';

// Initialize an empty message variable
$successMessage = '';

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
        // Set the success message to display in the HTML
        $successMessage = "Committee created successfully";
    } catch (PDOException $e) {
        // Rollback on error
        $db->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Committee</title>
    <link rel="stylesheet" href="lad.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
</head>
<body>
    <?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
    <div class="container">
        <!-- Display success message if available -->
        <?php if ($successMessage): ?>
              <div class="alert alert-success">
                  <?= htmlspecialchars($successMessage) ?>
              </div>
        <?php endif; ?>
        <h1>Create Committee</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="committee_name">Committee Name:</label>
                <input type="text" id="committee_name" name="committee_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="committee_function">Committee Function:</label>
                <textarea id="committee_function" name="committee_function" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="chamber">Chamber:</label>
                <select id="chamber" name="chamber" class="form-control" required>
                    <option value="">Select Chamber</option>
                    <option value="Senate">Senate</option>
                    <option value="House of Reps">House of Reps</option>
                </select>
            </div>
            <div class="form-group">
                <label for="members">Select Members:</label>
                <select id="members" name="members[]" class="form-control select2" multiple="multiple" required>
                    <!-- Members will be dynamically populated here -->
                </select>
                <small>Select up to 40 members.</small>
            </div>
            <button type="submit" class="btn btn-primary">Create Committee</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize Select2 for searchable dropdown
            $('#members').select2({
                placeholder: "Choose members",
                width: '100%'
            });

            // Dynamically load members based on chamber selection
            $('#chamber').on('change', function () {
                const chamber = $(this).val();

                // Clear existing options
                $('#members').empty();

                if (chamber) {
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
                                $('#members').append(new Option(member.name, member.id));
                            });
                        },
                        error: function () {
                            alert('Failed to fetch legislators. Please try again.');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
