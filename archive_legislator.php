<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chamber = $_POST['chamber'];
    $archiveLegislatorId = $_POST['archive_legislator_id'];
    $newLegislatorId = $_POST['new_legislator_id'] ?? null;

    try {
        $db->beginTransaction();

        // Archive the outgoing legislator
        $archiveStmt = $db->prepare("UPDATE legislators SET is_archived = TRUE WHERE id = ?");
        $archiveStmt->execute([$archiveLegislatorId]);

        if ($newLegislatorId) {
            // Set the predecessor for the incoming legislator
            $updateNewLegislatorStmt = $db->prepare("UPDATE legislators SET predecessor_id = ? WHERE id = ?");
            $updateNewLegislatorStmt->execute([$archiveLegislatorId, $newLegislatorId]);
        }

        $db->commit();
        $successMessage = "Legislator archived and predecessor set successfully.";
    } catch (PDOException $e) {
        $db->rollBack();
        $errorMessage = "Error: " . $e->getMessage();
    }
}

// Fetch chambers for dropdown
$chambers = ['Senate', 'House of Reps'];

// Fetch legislators based on selected chamber
$legislators = [];
if (isset($_POST['chamber'])) {
    $chamber = $_POST['chamber'];
    $stmt = $db->prepare("SELECT id, name, position, state FROM legislators WHERE is_archived = FALSE AND chamber = ?");
    $stmt->execute([$chamber]);
    $legislators = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive Legislator</title>
    <link rel="stylesheet" href="lad.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
</head>
<body>
<div class="container">
    <h2>Archive Legislator</h2>

    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php elseif (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="chamber">Select Chamber</label>
            <select name="chamber" id="chamber" class="form-control select2" onchange="this.form.submit()">
                <option value="">-- Select Chamber --</option>
                <?php foreach ($chambers as $chamberOption): ?>
                    <option value="<?= $chamberOption ?>" <?= isset($chamber) && $chamber === $chamberOption ? 'selected' : '' ?>>
                        <?= $chamberOption ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!empty($legislators)): ?>
            <div class="form-group">
                <label for="archive_legislator_id">Select Legislator to Archive</label>
                <select name="archive_legislator_id" id="archive_legislator_id" class="form-control select2" required>
                    <option value="">-- Select Legislator --</option>
                    <?php foreach ($legislators as $legislator): ?>
                        <option value="<?= $legislator['id'] ?>">
                            <?= htmlspecialchars($legislator['name'] . " - " . $legislator['position'] . " (" . $legislator['state'] . ")") ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="new_legislator_id">Select Replacement Legislator (Optional)</label>
                <select name="new_legislator_id" id="new_legislator_id" class="form-control select2">
                    <option value="">-- No Replacement --</option>
                    <?php foreach ($legislators as $legislator): ?>
                        <option value="<?= $legislator['id'] ?>">
                            <?= htmlspecialchars($legislator['name'] . " - " . $legislator['position'] . " (" . $legislator['state'] . ")") ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Archive Legislator</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
</body>
</html>
