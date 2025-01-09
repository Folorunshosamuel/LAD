<?php
include 'db_connect.php'; // Database connection file


// Handle form submission to log a defection
$successMessage = '';
$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $legislator_id = $_POST['legislator_id'];
    $new_party = $_POST['new_party'];
    $reason = $_POST['reason'];

    try {
        // Begin transaction
        $db->beginTransaction();

        // Fetch old party
        $stmt = $db->prepare("SELECT party FROM legislators WHERE id = ?");
        $stmt->execute([$legislator_id]);
        $legislator = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($legislator) {
            $old_party = $legislator['party'];

            // Log the defection in party_defections table
            $insertSql = "INSERT INTO party_defections (legislator_id, old_party, new_party, reason) 
                          VALUES (:legislator_id, :old_party, :new_party, :reason)";
            $stmt = $db->prepare($insertSql);
            $stmt->execute([
                ':legislator_id' => $legislator_id,
                ':old_party' => $old_party,
                ':new_party' => $new_party,
                ':reason' => $reason
            ]);

            // Update the party in the legislators table
            $updateSql = "UPDATE legislators SET party = :new_party WHERE id = :legislator_id";
            $stmt = $db->prepare($updateSql);
            $stmt->execute([
                ':new_party' => $new_party,
                ':legislator_id' => $legislator_id
            ]);

            $db->commit();
            $successMessage = "Defection logged successfully!";
        } else {
            $errorMessage = "Legislator not found.";
        }
    } catch (PDOException $e) {
        $db->rollBack();
        $errorMessage = "Error: " . $e->getMessage();
    }
}

// Fetch defection history
$defections = $db->query("SELECT pd.*, l.name AS legislator_name 
                          FROM party_defections pd
                          JOIN legislators l ON pd.legislator_id = l.id
                          ORDER BY pd.defection_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Party Defections</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="lad.css"> <!-- Your custom CSS -->
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-4">
    <h2>Track Party Defections</h2>

    <!-- Display success or error message -->
    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <!-- Defection Form -->
    <div class="card mb-4">
        <div class="card-header">Log a Defection</div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="chamber" class="form-label">Select Chamber</label>
                    <select id="chamber" class="form-select">
                        <option value="">-- Select Chamber --</option>
                        <option value="Senate">Senate</option>
                        <option value="House of Reps">House of Reps</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="legislator_id" class="form-label">Select Legislator</label>
                    <select name="legislator_id" id="legislator_id" class="form-select" required>
                        <option value="">-- Select Chamber First --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="new_party" class="form-label">New Party</label>
                    <input type="text" name="new_party" id="new_party" class="form-control" placeholder="Enter new party" required>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Reason for Defection</label>
                    <textarea name="reason" id="reason" class="form-control" rows="4" placeholder="Enter reason"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Log Defection</button>
            </form>
        </div>
    </div>

    <!-- Defection History Table -->
    <h3>Defection History</h3>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Legislator</th>
                <th>Old Party</th>
                <th>New Party</th>
                <th>Defection Date</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($defections) > 0): ?>
                <?php foreach ($defections as $index => $defection): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($defection['legislator_name']); ?></td>
                        <td><?= htmlspecialchars($defection['old_party']); ?></td>
                        <td><?= htmlspecialchars($defection['new_party']); ?></td>
                        <td><?= htmlspecialchars($defection['defection_date']); ?></td>
                        <td><?= nl2br(htmlspecialchars($defection['reason'] ?? 'N/A')); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No defections logged yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#legislator_id').select2({ placeholder: "Select a legislator" });

        // Load legislators based on chamber selection
        $('#chamber').on('change', function () {
            const chamber = $(this).val();
            $('#legislator_id').empty().append('<option value="">-- Loading... --</option>');

            if (chamber) {
                $.ajax({
                    url: 'fetch_legislators.php',
                    method: 'GET',
                    data: { chamber },
                    success: function (data) {
                        $('#legislator_id').empty().append('<option value="">-- Select Legislator --</option>');
                        JSON.parse(data).forEach(legislator => {
                            $('#legislator_id').append(new Option(legislator.name, legislator.id));
                        });
                    },
                    error: function () {
                        alert('Failed to fetch legislators.');
                    }
                });
            }
        });
    });
</script>
</body>
</html>
