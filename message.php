<?php
include 'header.php';
include 'memberfetch.php';
include 'db_connect.php'; // Ensure the correct database connection is established

// Define upload directory
$uploadDir = 'uploads/messages/';

// Check if form is submitted
$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION["id"];
    $receiver_id = $_POST['receiver_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $filePath = null;

    try {
        // Verify that receiver exists in users table
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE id = :receiver_id");
        $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->execute();
        $receiver_exists = $stmt->fetchColumn();

        if (!$receiver_exists) {
            throw new Exception("Error: The specified user does not exist.");
        }

        // Handle file upload if a file is attached
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/mpeg'];
            $fileType = mime_content_type($_FILES['attachment']['tmp_name']);

            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Invalid file type. Only images (JPEG, PNG, GIF) and videos (MP4, MPEG) are allowed.");
            }

            // Generate a unique filename and save the file
            $fileName = uniqid() . '_' . basename($_FILES['attachment']['name']);
            $filePath = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $filePath)) {
                throw new Exception("Failed to upload file.");
            }
        }

        // Insert message into the database
        $sql = "INSERT INTO messages (sender_id, receiver_id, subject, message, attachment) 
                VALUES (:sender_id, :receiver_id, :subject, :message, :attachment)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
        $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':attachment', $filePath, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $successMessage = "Message sent successfully!";
        } else {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Database error: " . $errorInfo[2]);
        }
    } catch (Exception $e) {
        $successMessage = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message</title>
    <link rel="stylesheet" href="lad.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Success or error message -->
        <?php if ($successMessage): ?>
            <div class="alert alert-info"><?= htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <div class="profile-container">
            <h5>Send a message to <?= htmlspecialchars($member['name'] ?? 'User'); ?></h5>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($member['id'] ?? ''); ?>">

                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter subject" required>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea name="message" id="message" class="form-control" rows="4" placeholder="Type your message" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="attachment" class="form-label">Attach File (Images/Videos only)</label>
                    <input type="file" name="attachment" id="attachment" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>
