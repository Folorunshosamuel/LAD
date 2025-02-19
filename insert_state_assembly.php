<?php
// Database connection
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_FILES["csv_file"]) && $_FILES["csv_file"]["error"] == 0) {
        $fileTmpPath = $_FILES["csv_file"]["tmp_name"];
        $fileType = mime_content_type($fileTmpPath);

        // Validate file type (ensure it's a CSV)
        if ($fileType !== "text/plain" && $fileType !== "text/csv") {
            die("Error: Only CSV files are allowed.");
        }

        // Open the file and read data
        if (($handle = fopen($fileTmpPath, "r")) !== false) {
            // Skip the first row if it contains headers
            fgetcsv($handle);

            $insertQuery = $db->prepare("INSERT INTO state_assembly_members 
                (name, state, constituency, party, gender, position, dob, chamber, email, phone, image) 
                VALUES (:name, :state, :constituency, :party, :gender, :position, NULL, 'State Assembly', NULL, NULL, 'uploads/avatar.webp')");

            $rowCount = 0;

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                // Ensure all required fields exist
                if (count($data) < 6) {
                    continue;
                }

                // Map CSV columns to variables
                [$name, $state, $constituency, $party, $gender, $position] = $data;

                // Insert record into the database
                $insertQuery->execute([
                    ':name' => trim($name),
                    ':state' => trim($state),
                    ':constituency' => trim($constituency),
                    ':party' => trim($party),
                    ':gender' => trim($gender),
                    ':position' => trim($position)
                ]);

                $rowCount++;
            }

            fclose($handle);
            echo "✅ Successfully uploaded $rowCount records!";
        } else {
            die("Error: Unable to open the file.");
        }
    } else {
        die("Error: Please upload a valid CSV file.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Upload - State Assembly Members</title>
    <link rel="stylesheet" href="lad.css"> <!-- Ensure styling matches existing UI -->
</head>
<body>
    <div class="container">
        <h2>Bulk Upload State Assembly Members</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="csv_file" accept=".csv" required>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
