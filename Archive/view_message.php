<?php
include 'header.php'; // Include the header at the beginning of the dashboard
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: signin.php");
    exit;
}

// Get the message ID from the URL
$message_id = $_GET['id'] ?? null;

if (!$message_id) {
    echo "<p>Message not found or no permission to view.</p>";
    exit;
}

// Fetch the original message and replies
$sql = "SELECT m.*, CONCAT(u.fName, ' ', u.lName) AS sender_name
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE m.id = :id OR m.parent_id = :id
        ORDER BY m.created_at ASC";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $message_id, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no messages are found
if (!$messages) {
    echo "<p>Message not found or you do not have permission to view this message.</p>";
    exit;
}

// Mark the original message as read if the viewer is the receiver
$originalMessage = $messages[0];
if (!$originalMessage['is_read'] && $originalMessage['receiver_id'] == $_SESSION['id']) {
    $update = $db->prepare("UPDATE messages SET is_read = 1 WHERE id = :id");
    $update->bindParam(':id', $message_id, PDO::PARAM_INT);
    $update->execute();
}

// Get the last message for the reply form
$lastMessage = end($messages);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message</title>
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
            <h2 class="mb-3"><?= htmlspecialchars($originalMessage['subject'] ?? 'No Subject'); ?></h2>

            <!-- Display all messages in the thread -->
            <?php foreach ($messages as $index => $msg): ?>
              <div class="message">
                  <p><strong>From:</strong> <?= htmlspecialchars($msg['sender_name']); ?></p>
                  <p><strong>Date:</strong> <?= htmlspecialchars($msg['created_at']); ?></p>
                  <p><?= nl2br(htmlspecialchars($msg['message'])); ?></p>
                  <!-- Show the Reply button only for the last message -->
                  <?php if ($index === count($messages) - 1): ?>
                      <a id="btnCompose" href="" class="btn btn-az-primary btn-compose">Reply</a>
                  <?php endif; ?>
              </div>
              <hr>
          <?php endforeach; ?>


            <!-- Reply Form -->
            <div class="az-mail-compose">
                <div>
                    <div class="container">
                        <div class="az-mail-compose-box">
                            <div class="az-mail-compose-header">
                                <span>Reply to Message</span>
                                <nav class="nav">
                                    <a href="" class="nav-link"><i class="fas fa-minus"></i></a>
                                    <a href="" class="nav-link"><i class="fas fa-compress"></i></a>
                                    <a href="" class="nav-link"><i class="fas fa-times"></i></a>
                                </nav>
                            </div><!-- az-mail-compose-header -->
                            <div class="az-mail-compose-body">
                                <form action="send_reply.php" method="post">
                                    <input type="hidden" name="parent_id" value="<?= htmlspecialchars($lastMessage['id']); ?>">
                                    <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($lastMessage['sender_id']); ?>">

                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input type="text" name="subject" id="subject" class="form-control" 
                                               value="<?= htmlspecialchars($lastMessage['subject']); ?>" readonly>
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
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>
