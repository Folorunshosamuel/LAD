<?php
    include 'header.php'; // Include the header at the beginning of the dashboard
?>
<?php
include 'db_connect.php';

// Assuming the user's ID is stored in session after login
$user_id = $_SESSION['id']; 

// Modify the SQL query to fetch messages for the logged-in user
$sql = "SELECT m.id, m.sender_id, m.receiver_id, m.subject, m.message, m.created_at, m.is_read,
               CONCAT(u.fName, ' ', u.lName) AS sender_name, l.name AS receiver_name
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        JOIN legislators l ON m.receiver_id = l.id
        WHERE m.receiver_id = :user_id
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
    <title>User Inbox</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="lad.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Menu -->
        <div class="col-md-3 bg-light sidebar">
            <button is="#btnCompose" class="btn btn-primary btn-block mb-3">Compose</button>
            <ul class="list-unstyled">
                <li><a href="user_inbox.php" class="d-flex align-items-center mb-2"><i class="fas fa-inbox mr-2"></i> Inbox</a></li>
                <li><a href="user_outbox.php" class="d-flex align-items-center mb-2"><i class="fas fa-paper-plane mr-2"></i> Sent Mail</a></li>
            </ul>
        </div>
        
        <!-- Main Inbox Content -->
        <div class="col-md-9">
            <h2 class="mb-3">Inbox</h2>
            <p class="text-muted">You have <?= count(array_filter($messages, fn($msg) => !$msg['is_read'])) ?> unread messages</p>

            <!-- Message List -->
            <div class="list-group">
                <?php foreach ($messages as $msg): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center" style="justify-content: space-between">
                            <input type="checkbox" class="mr-3">
                            <span class="mr-5"><?= $msg['sender_name']; ?></span>
                            <span class="mr-5 font-weight-bold"><?= $msg['subject']; ?></span>
                            <span class="mr-5 "><?= $msg['is_read'] ? 'Read' : 'Unread'; ?></td></span>
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
          <div class="form-group">
            <label class="form-label">To</label>
            <div><?= $msg['sender_name']; ?></div>
          </div><!-- form-group -->
          <div class="form-group">
            <label class="form-label">Subject</label>
            <div>RE: <?= $msg['subject']; ?></div>
          </div><!-- form-group -->
          <div class="form-group">
            <textarea class="form-control" rows="8" placeholder="Write your message here..."></textarea>
          </div><!-- form-group -->
          <div class="form-group mg-b-0">
            <nav class="nav">
              <a href="" class="nav-link" data-bs-toggle="tooltip" title="Add attachment"><i class="fas fa-paperclip"></i></a>
              <a href="" class="nav-link" data-bs-toggle="tooltip" title="Add photo"><i class="far fa-image"></i></a>
              <a href="" class="nav-link" data-bs-toggle="tooltip" title="Add link"><i class="fas fa-link"></i></a>
              <a href="" class="nav-link" data-bs-toggle="tooltip" title="Emoticons"><i class="far fa-smile"></i></a>
              <a href="" class="nav-link" data-bs-toggle="tooltip" title="Discard"><i class="far fa-trash-alt"></i></a>
            </nav>
            <button class="btn btn-primary">Send</button>
          </div><!-- form-group -->
        </div><!-- az-mail-compose-body -->
      </div><!-- az-mail-compose-box -->
    </div><!-- container -->
  </div>
</div><!-- az-mail-compose -->


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

