<?php
    include 'header.php'; // Include the header at the beginning of the dashboard
?>
<?php
include 'db_connect.php';

$message_id = $_GET['id'];

/* // Fetch the message details
$sql = "SELECT * FROM messages WHERE id = :id AND (receiver_id = :receiver_id OR sender_id = :sender_id)";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $message_id, PDO::PARAM_INT);
$stmt->bindParam(':receiver_id', $_SESSION["id"], PDO::PARAM_INT);
$stmt->bindParam(':sender_id', $_SESSION["id"], PDO::PARAM_INT);
$stmt->execute();
$message = $stmt->fetch(PDO::FETCH_ASSOC);  */

$sql = "SELECT 
        messages.*, CONCAT(users.fName, ' ', users.lName) AS sender_name FROM messages JOIN users ON messages.sender_id = users.id WHERE messages.id = :id AND 
        (messages.receiver_id = :receiver_id OR messages.sender_id = :sender_id)";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $message_id, PDO::PARAM_INT);
        $stmt->bindParam(':receiver_id', $_SESSION["id"], PDO::PARAM_INT);
        $stmt->bindParam(':sender_id', $_SESSION["id"], PDO::PARAM_INT);
        $stmt->execute();
        $message = $stmt->fetch(PDO::FETCH_ASSOC);


// Check if the message was found
if ($message) {
    // Mark message as read if it's unread
    if (!$message['is_read']) {
        $update = $db->prepare("UPDATE messages SET is_read = 1 WHERE id = :id");
        $update->bindParam(':id', $message_id, PDO::PARAM_INT);
        $update->execute();
    }
} else {
    // Message not found, display an error message
    echo "<p>Message not found or you do not have permission to view this message.</p>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Inbox</title>
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
            <h2 class="mb-3"><?= htmlspecialchars($message['subject'] ?? 'No subject'); ?></h2>

            <!-- Message List -->
            <div>
                <p><strong>From:</strong> <?= htmlspecialchars($message['sender_name'] ?? 'Unknown'); ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($message['created_at'] ?? 'Unknown date'); ?></p>
                <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($message['message'] ?? 'No message content available.')); ?></p>
                <a id="btnCompose" href="" class="btn btn-az-primary btn-compose">Reply</a>
            </div>
        </div>
    </div>
</div>
<div class="az-mail-compose">
  <div>
    <div class="container">
      <div class="az-mail-compose-box">
        <div class="az-mail-compose-header">
          <span>New Message</span>
          <nav class="nav">
            <a href="" class="nav-link"><i class="fas fa-minus"></i></a>
            <a href="" class="nav-link"><i class="fas fa-compress"></i></a>
            <a href="" class="nav-link"><i class="fas fa-times"></i></a>
          </nav>
        </div><!-- az-mail-compose-header -->
        <div class="az-mail-compose-body">
            
          <form action="send_reply.php" method="post">
            <input type="hidden" name="parent_id" value="<?= htmlspecialchars($message['id']); ?>">
            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($message['sender_id']); ?>">
            
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" name="subject" id="subject" class="form-control" value="RE: <?= htmlspecialchars($message['subject']); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" class="form-control" rows="5" placeholder="Write your reply here..." required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
        </div><!-- az-mail-compose-body -->
      </div><!-- az-mail-compose-box -->
    </div><!-- container -->
  </div>
</div><!-- az-mail-compose -->
<script>
    $(function(){
        'use strict'

        $('#checkAll').on('click', function(){
          if($(this).is(':checked')) {
            $('.az-mail-list .ckbox input').each(function(){
              $(this).closest('.az-mail-item').addClass('selected');
              $(this).attr('checked', true);
            });

            $('.az-mail-options .btn:not(:first-child)').removeClass('disabled');
          } else {
            $('.az-mail-list .ckbox input').each(function(){
              $(this).closest('.az-mail-item').removeClass('selected');
              $(this).attr('checked', false);
            });

            $('.az-mail-options .btn:not(:first-child)').addClass('disabled');
          }
        });

        $('.az-mail-item .az-mail-checkbox input').on('click', function(){
          if($(this).is(':checked')) {
            $(this).attr('checked', false);
            $(this).closest('.az-mail-item').addClass('selected');

            $('.az-mail-options .btn:not(:first-child)').removeClass('disabled');

          } else {
            $(this).attr('checked', true);
            $(this).closest('.az-mail-item').removeClass('selected');

            if(!$('.az-mail-list .selected').length) {
              $('.az-mail-options .btn:not(:first-child)').addClass('disabled');
            }
          }
        });

        $('.az-mail-star').on('click', function(e){
          $(this).toggleClass('active');
        });

        $('#btnCompose').on('click', function(e){
          e.preventDefault();
          $('.az-mail-compose').show();
        });

        $('.az-mail-compose-header a:first-child').on('click', function(e){
          e.preventDefault();
          $('.az-mail-compose').toggleClass('az-mail-compose-minimize');
        })

        $('.az-mail-compose-header a:nth-child(2)').on('click', function(e){
          e.preventDefault();
          $(this).find('.fas').toggleClass('fa-compress');
          $(this).find('.fas').toggleClass('fa-expand');
          $('.az-mail-compose').toggleClass('az-mail-compose-compress');
          $('.az-mail-compose').removeClass('az-mail-compose-minimize');
        });

        $('.az-mail-compose-header a:last-child').on('click', function(e){
          e.preventDefault();
          $('.az-mail-compose').hide(100);
          $('.az-mail-compose').removeClass('az-mail-compose-minimize');
        });


      });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>