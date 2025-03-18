<?php
// Connect to the database
include 'db_connect.php';

// // Get member type and id from URL
// $memberType = $_GET['type'];
$memberId = $_GET['id'];

// Initialize an empty message variable
$successMessage = '';

// // Fetch current data for the selected member
// $tableName = $memberType === 'senate' ? 'senateMembers' : 'horMembers';
$query = $db->prepare("SELECT * FROM legislators WHERE id = ?");
$query->execute([$memberId]);
$member = $query->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];
    $party = $_POST['party'];
    $biography = $_POST['biography'];

    // Handle image upload
    $imagePath = $member['image'];
    if (!empty($_FILES['image']['name'])) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    // Update member information in the database
    $updateQuery = $db->prepare("UPDATE legislators SET name = ?, dob = ?, gender = ?, position = ?, party = ?, biography = ?, image = ? WHERE id = ?");
    $updateQuery->execute([$name, $dob, $gender, $position, $party, $biography, $imagePath, $memberId]);

    // Set the success message to display in the HTML
    $successMessage = "Profile updated successfully!";
}

// Re-fetch the updated data
$query->execute([$memberId]);
$member = $query->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - <?= htmlspecialchars($member['name']) ?></title>
    <link rel="stylesheet" href="lad.css"> <!-- Adjust the path to your stylesheet -->

    <style>
        .edit-profile-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .edit-profile-container h2 {
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
        .form-group textarea {
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
        .current-image {
            text-align: center;
            margin-top: 15px;
        }
        .current-image img {
            width: 120px;
            border-radius: 50%;
        }
        .alert {
            padding: 0.75rem 3.25rem;
            margin-left: 25rem;
            border: 1px solid transparent;
            border-radius: 3px;
            margin-right: 25rem;
            font-size: larger; }
        
        .alert-success {
            color: #1f5c01;
            background-color: #d8efcc;
            border-color: #c8e9b8; }
            .alert-success hr {
                border-top-color: #b9e3a5; }
            .alert-success .alert-link {
                color: #0e2a00; }

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

    <div class="edit-profile-container">
        <h2>Edit Profile of <?= htmlspecialchars($member['name']) ?></h2>
        

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($member['name'] ?? 'N/A') ?>">
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($member['dob'] ?? 'N/A') ?>">
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <input type="text" name="gender" id="gender" value="<?= htmlspecialchars($member['gender'] ?? 'N/A') ?>">
            </div>

            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" name="position" id="position" value="<?= htmlspecialchars($member['position'] ?? 'N/A') ?>">
            </div>

            <div class="form-group">
                <label for="party">Party</label>
                <input type="text" name="party" id="party" value="<?= htmlspecialchars($member['party'] ?? 'N/A') ?>">
            </div>

            <div class="form-group">
                <label for="biography">Biography</label>
                <textarea name="biography" id="biography"><?= htmlspecialchars($member['biography'] ?? 'N/A') ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Profile Image</label>
                <input type="file" name="image" id="image">
            </div>

            <?php if ($member['image']): ?>
                <div class="current-image">
                    <p>Current Image:</p>
                    <img src="<?= htmlspecialchars($member['image']) ?>" alt="Profile Image">
                </div>
            <?php endif; ?>

            <button type="submit" class="submit-btn">Update Profile</button>
        </form>
    </div>
</body>
</html>
