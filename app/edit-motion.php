<?php
// Include database connection
include 'db_connect.php';

// Get the bill ID from the URL
$motionId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($motionId === null) {
    die("Motion ID is not defined in the URL.");
}

// Fetch the bill’s data
$query = $db->prepare("SELECT * FROM Motions WHERE id = ?");
$query->execute([$motionId]);
$motion = $query->fetch();

if (!$motion) {
    die("Motion not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $chamber = $_POST['chamber'];
    $dateFiled = !empty($_POST['dateFiled']) ? $_POST['dateFiled'] : null;
    $resolution = !empty($_POST['resolution']) ? $_POST['resolution'] : null;
    $committeeReferred = !empty($_POST['committeeReferred']) ? $_POST['committeeReferred'] : null;
    $committeeReportDate = !empty($_POST['committeeReportDate']) ? $_POST['committeeReportDate'] : null;
 
    // Handle file upload
    $motionFile = $motion['motionFile']; // Keep the current file if none uploaded
    if (!empty($_FILES['motionFile']['name'])) {
        $motionFile = 'motions/' . basename($_FILES['motionFile']['name']);
        move_uploaded_file($_FILES['motionFile']['tmp_name'], $motionFile);
    }

    // Update the bill information in the database
    $updateQuery = $db->prepare("UPDATE Motions SET title = ?, chamber = ?, dateFiled = ?, resolution = ?, committeeReferred = ?, committeeReportDate = ?, motionFile = ? WHERE id = ?");
    $updateQuery->execute([$title, $chamber, $dateFiled, $resolution, $committeeReferred, $committeeReportDate, $motionFile, $motionId]);

    echo "<p class='success-message'>Bill updated successfully!</p>";

     // Re-fetch the updated data
     $query->execute([$motionId]);
     $motion = $query->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Motion - <?= htmlspecialchars($motion['title']) ?></title>
    <link rel="stylesheet" href="lad.css"> <!-- Adjust path to your CSS file -->

    <style>
        .edit-bill-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .edit-bill-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-group input[type="file"] {
            font-size: 16px;
        }
        .current-file {
            text-align: center;
            margin-top: 15px;
        }
        .success-message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
    <div class="edit-bill-container">
        <h2>Edit Motion - <?= htmlspecialchars($motion['title']) ?></h2>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?= htmlspecialchars($motion['title']) ?>">
            </div>

            <div class="form-group">
                <label for="chamber">Chamber</label>
                <select class="form-control select2" name="chamber" id="chamber">
                    <option value="<?= htmlspecialchars($motion['chamber']) ?>"><?= htmlspecialchars($motion['chamber']) ?></option>
                    <option value="Senate">Senate</option>
                    <option value="House of Reps">House of Reps</option>
                </select>
            </div>

            <div class="form-group">
                <label for="firstReading">Date Filed</label>
                <input type="date" name="dateFiled" id="dateFiled" value="<?= htmlspecialchars($motion['dateFiled']) ?>">
            </div>

            <div class="form-group">
                <label for="resolution">Resolution</label>
                <input type="text" name="resolution" id="resolution" value="<?= htmlspecialchars($motion['resolution']) ?>">
            </div>

            <div class="form-group">
                <label for="committeeReferred">committee Referred to</label>
                <input type="text" name="committeeReferred" id="committeeReferred" value="<?= htmlspecialchars($motion['committeeReferred']) ?>">
            </div>

            <div class="form-group">
                <label for="committeeReportDate">Committe Report Date</label>
                <input type="date" name="committeeReportDate" id="committeeReportDate" value="<?= htmlspecialchars($motion['committeeReportDate']) ?>">
            </div>

            <div class="form-group">
                <label for="motionFile">Upload Motion</label>
                <input type="file" name="motionFile" id="motionFile">
            </div>

            <?php if ($motion['motionFile']): ?>
                <div class="current-file">
                    <p>Current File: <a href="<?= htmlspecialchars($motion['motionFile']) ?>" target="_blank">View Document</a></p>
                </div>
            <?php endif; ?>

            <button type="submit" class="submit-btn">Update Motion</button>
        </form>
    </div>
</body>
</html>
