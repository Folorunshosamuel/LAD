<?php
    include 'header.php'; // Include the header at the beginning of the dashboard
    ?>
<?php
include 'db_connect.php';

// Assuming the user's ID is stored in session after login
$user_id = $_SESSION['id']; 

// Modify the SQL query to fetch messages for the logged-in user
// Modify the SQL query to fetch messages for the logged-in user
$sql = "SELECT m.id, m.sender_id, m.receiver_id, m.subject, m.message, m.created_at,
               CONCAT(u.fName, ' ', u.lName) AS sender_name, 
               CONCAT(r.fName, ' ', r.lName) AS receiver_name
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        JOIN users r ON m.receiver_id = r.id
        WHERE m.sender_id = :user_id
        ORDER BY m.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Bind user_id as parameter
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Messages</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="lad.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3 bg-light sidebar">
            <button class="btn btn-primary btn-block mb-3">Compose</button>
            <ul class="list-unstyled">
                <li><a href="user_inbox.php" class="d-flex align-items-center mb-2"><i class="fas fa-inbox mr-2"></i> Inbox</a></li>
                <li><a href="user_outbox.php" class="d-flex align-items-center mb-2"><i class="fas fa-paper-plane mr-2"></i> Sent Mail</a></li>
            </ul>
        </div>
        
        <!-- Main Inbox Content -->
        <div class="col-md-9">
            <h2 class="mb-3">Sent Messages</h2>

            <!-- Message List -->
            <div class="list-group">
                <?php foreach ($messages as $msg): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="justify-content: space-between">
                            <input type="checkbox" class="mr-3">
                            <span class="mr-5"><?= $msg['receiver_name']; ?></span>
                            <span class="mr-5 font-weight-bold"><?= $msg['subject']; ?></span>
                            <span class="mr-5 "><?= date("M d, Y h:i A", strtotime($msg['created_at'])); ?></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="text-muted"><a href="view_message.php?id=<?= $msg['id']; ?>" class="view-link">View</a></small>
                            <!-- <i class="fas fa-paperclip ml-3"></i> -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

