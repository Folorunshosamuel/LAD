<?php
session_start();
include 'db_connect.php';

$message_id = $_GET['id'];

// Fetch the message details
$sql = "SELECT * FROM messages WHERE id = :id AND receiver_id = :receiver_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $message_id, PDO::PARAM_INT);
$stmt->bindParam(':receiver_id', $_SESSION["id"], PDO::PARAM_INT);
$stmt->execute();
$message = $stmt->fetch(PDO::FETCH_ASSOC);

// Mark message as read
if ($message && !$message['is_read']) {
    $update = $db->prepare("UPDATE messages SET is_read = 1 WHERE id = :id");
    $update->bindParam(':id', $message_id, PDO::PARAM_INT);
    $update->execute();
}
?>

<h2>Message Details</h2>
<p><strong>From:</strong> <?= htmlspecialchars($message['sender_id']); ?></p>
<p><strong>Subject:</strong> <?= htmlspecialchars($message['subject']); ?></p>
<p><strong>Date:</strong> <?= htmlspecialchars($message['created_at']); ?></p>
<p><strong>Message:</strong> <?= nl2br(htmlspecialchars($message['message'])); ?></p>

<!-- Reply Form -->
<form action="send_reply.php" method="POST">
    <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($message['sender_id']); ?>">
    <input type="hidden" name="original_message_id" value="<?= $message['id']; ?>">
    <div class="form-group">
        <label for="reply_message">Reply:</label>
        <textarea name="reply_message" id="reply_message" class="form-control" rows="4" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send Reply</button>
</form>
