<?php
// Include database connection
include 'db_connect.php';

// Get the bill ID from the URL
$billId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($billId === null) {
    die("Bill ID is not defined in the URL.");
}

// Initialize an empty message variable
$successMessage = '';

// Fetch the bill’s data
$query = $db->prepare("SELECT * FROM Bills WHERE id = ?");
$query->execute([$billId]);
$bill = $query->fetch();

if (!$bill) {
    die("Bill not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billNum = $_POST['billNum'];
    $title = $_POST['title'];
    $billStatus = $_POST['billStatus'];
    $firstReading = !empty($_POST['firstReading']) ? $_POST['firstReading'] : null;
    $secondReading = !empty($_POST['secondReading']) ? $_POST['secondReading'] : null;
    $thirdReading = !empty($_POST['thirdReading']) ? $_POST['thirdReading'] : null;
    $committeeReferred = !empty($_POST['committeeReferred']) ? $_POST['committeeReferred'] : null;
    $consolidatedWith = is_numeric($_POST['consolidatedWith']) ? intval($_POST['consolidatedWith']) : null;
    $billAnalysis = $_POST['billAnalysis'];
 
    // Handle file upload
    $billFile = $bill['billFile']; // Keep the current file if none uploaded
    if (!empty($_FILES['billFile']['name'])) {
        $billFile = 'bills/' . basename($_FILES['billFile']['name']);
        move_uploaded_file($_FILES['billFile']['tmp_name'], $billFile);
    }

    // Update the bill information in the database
    $updateQuery = $db->prepare("UPDATE Bills SET billNum = ?, title = ?, billStatus = ?, firstReading = ?, secondReading = ?, thirdReading = ?, committeeReferred = ?, consolidatedWith = ?, billAnalysis = ?, billFile = ? WHERE id = ?");
    $updateQuery->execute([$billNum, $title, $billStatus, $firstReading, $secondReading, $thirdReading, $committeeReferred, $consolidatedWith, $billAnalysis, $billFile, $billId]);

    // Set the success message to display in the HTML
    $successMessage = "Bill updated successfully!";

    // Fetch Committee for the dropdown
    $committeesQuery = $db->query("SELECT id, name FROM committees"); // Adjust for `horMembers` if needed
    $committees = $committeesQuery->fetchAll();
    
    // Re-fetch the updated data
     $query->execute([$billId]);
     $bill = $query->fetch();



}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bill - <?= htmlspecialchars($bill['title']) ?></title>
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
    <!-- Display success message if available -->
        <?php if ($successMessage): ?>
              <div class="alert alert-success">
                  <?= htmlspecialchars($successMessage) ?>
              </div>
        <?php endif; ?>
    <div class="edit-bill-container">
        <h2>Edit Bill - <?= htmlspecialchars($bill['title']) ?></h2>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="billNum">Bill Number</label>
                <input type="text" name="billNum" id="billNum" value="<?= htmlspecialchars($bill['billNum']) ?>">
            </div>
            
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?= htmlspecialchars($bill['title']) ?>">
            </div>

            <div class="form-group">
                <label for="billStatus">Status</label>
                <select name="billStatus" id="billStatus">
                    <option value="Assented" <?= $bill['billStatus'] == 'Assented' ? 'selected' : '' ?>>Assented</option>
                    <option value="Pending" <?= $bill['billStatus'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Passed" <?= $bill['billStatus'] == 'Passed' ? 'selected' : '' ?>>Passed</option>
                    <option value="Rejected" <?= $bill['billStatus'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                    <option value="Vetoed" <?= $bill['billStatus'] == 'Vetoed' ? 'selected' : '' ?>>Vetoed</option>
                    <option value="Withdrawn" <?= $bill['billStatus'] == 'Withdrawn' ? 'selected' : '' ?>>Withdrawn</option>
                </select>
            </div>

            <div class="form-group">
                <label for="firstReading">First Reading</label>
                <input type="date" name="firstReading" id="firstReading" value="<?= htmlspecialchars($bill['firstReading'] ?? 'N/A' ) ?>">
            </div>

            <div class="form-group">
                <label for="secondReading">Second Reading</label>
                <input type="date" name="secondReading" id="secondReading" value="<?= htmlspecialchars($bill['secondReading']  ?? 'N/A') ?>">
            </div>

            <div class="form-group">
                <label for="thirdReading">Third Reading</label>
                <input type="date" name="thirdReading" id="thirdReading" value="<?= htmlspecialchars($bill['thirdReading'] ?? 'N/A' ) ?>">
            </div>

            <div class="form-group">
                <label for="committeeReferred">committee Referred to</label>
                <input type="text" name="committeeReferred" id="committeeReferred" value="<?= htmlspecialchars($bill['committeeReferred'] ?? 'N/A') ?>">
            </div>

            <div class="form-group">
                <label for="consolidatedWith">Consolidated With</label>
                <input type="text" name="consolidatedWith" id="consolidatedWith" value="<?= htmlspecialchars($bill['consolidatedWith']?? 'N/A') ?>">
            </div>

            <div class="form-group">
                <label for="billAnalysis">Bill Summary</label>
                <textarea name="billAnalysis" id="billAnalysis"><?= htmlspecialchars($bill['billAnalysis'] ?? 'N/A') ?></textarea>
            </div>

            <div class="form-group">
                <label for="billFile">Upload Bill</label>
                <input type="file" name="billFile" id="billFile">
            </div>

            <?php if ($bill['billFile']): ?>
                <div class="current-file">
                    <p>Current File: <a href="<?= htmlspecialchars($bill['billFile']) ?>" target="_blank">View Document</a></p>
                </div>
            <?php endif; ?>

            <button type="submit" class="submit-btn">Update Bill</button>
        </form>
    </div>
</body>
</html>
