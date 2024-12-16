<?php
// Include database connection
include 'db_connect.php';

$uploadMessage = ""; // To display upload success or error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
        $csvFile = $_FILES['csvFile']['tmp_name'];

        // Open and read the CSV file
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            // Skip the header row
            fgetcsv($handle, 1000, ",");

            // Prepare the SQL statement
            $stmt = $db->prepare("INSERT INTO legislators (name, gender, position, state, constituency, party, chamber) VALUES (?, ?, ?, ?, ?, ?, ?)");

            // Read each row and insert into the database
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Replace non-ASCII characters in the `party` field (assumed to be at index 4)
                $data[0] = preg_replace('/[^\x20-\x7E]/', '', $data[0]);
                $data[4] = preg_replace('/[^\x20-\x7E]/', '', $data[4]);
                $data[5] = preg_replace('/[^\x20-\x7E]/', '', $data[5]);
                $data[6] = preg_replace('/[^\x20-\x7E]/', '', $data[6]);

                // Bind values from CSV columns to the prepared statement
                $stmt->execute([
                    $data[0], // name
                    $data[1], // gender
                    $data[2], // position
                    $data[3], // state
                    $data[4], // constituency
                    $data[5],  // party (with non-ASCII characters removed)
                    $data[6]  // chamber
                ]);
            }

            fclose($handle);
            $uploadMessage = "<p style='color: green;'>Bulk upload successful!</p>";
        } else {
            $uploadMessage = "<p style='color: red;'>Error opening the file.</p>";
        }
    } else {
        $uploadMessage = "<p style='color: red;'>Error uploading the file.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Upload - HoR Members</title>
</head>
<body>
    <h2>Bulk Upload House of Representatives Members</h2>
    
    <!-- Display upload success or error message -->
    <?= $uploadMessage ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="csvFile">Select CSV File:</label>
        <input type="file" name="csvFile" id="csvFile" accept=".csv" required>
        <button type="submit">Upload</button>
    </form>

    <p>CSV Format: name,email,phone,dob,party,state,constituency,position</p>
</body>
</html>
