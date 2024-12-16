<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['csv_file']['tmp_name'];

        // Open the CSV file for reading
        if (($handle = fopen($file, 'r')) !== FALSE) {
            // Begin a transaction
            $db->beginTransaction();
            try {
                // Read the CSV headers
                $headers = fgetcsv($handle);

                while (($data = fgetcsv($handle)) !== FALSE) {
                    // Map CSV columns to variables
                    $name = trim($data[0]);
                    $function = trim($data[1]);
                    $chamber = trim($data[2]);
                    $members = explode(',', trim($data[3])); // Member IDs as an array

                    // Insert committee into the committees table
                    $insertCommittee = $db->prepare("
                        INSERT INTO committees (name, committee_function, chamber)
                        VALUES (:name, :committee_function, :chamber)
                    ");
                    $insertCommittee->execute([
                        ':name' => $name,
                        ':committee_function' => $function,
                        ':chamber' => $chamber,
                    ]);

                    // Get the last inserted committee ID
                    $committee_id = $db->lastInsertId();

                    // Insert members into the committee_members table
                    $insertMembers = $db->prepare("
                        INSERT INTO committee_members (committee_id, legislator_id)
                        VALUES (:committee_id, :legislator_id)
                    ");
                    foreach ($members as $member_id) {
                        $insertMembers->execute([
                            ':committee_id' => $committee_id,
                            ':legislator_id' => $member_id,
                        ]);
                    }
                }

                // Commit the transaction
                $db->commit();
                echo "Batch upload successful!";
            } catch (Exception $e) {
                // Rollback on error
                $db->rollBack();
                echo "Error: " . $e->getMessage();
            }
            fclose($handle);
        } else {
            echo "Unable to open the uploaded file.";
        }
    } else {
        echo "File upload error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch Upload Committees</title>
    <link rel="stylesheet" href="lad.css">
</head>
<body>
<div class="container">
    <h1>Batch Upload Committees</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="csv_file">Upload CSV File</label>
            <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
</body>
</html>
