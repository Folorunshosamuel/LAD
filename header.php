<?php
// Start the session
session_start();

// Include database connection
include 'db_connect.php';
include 'utils.php';

// Define session timeout duration (e.g., 900 seconds = 15 minutes)
define('SESSION_TIMEOUT', 1500); 

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: signin.php");
    exit;
}

// Check if last activity is set
if (isset($_SESSION['last_activity'])) {
    $inactiveTime = time() - $_SESSION['last_activity'];

    // If user has been inactive for too long, destroy session and redirect to signin page
    if ($inactiveTime > SESSION_TIMEOUT) {
        session_unset(); // Clear session data
        session_destroy(); // Destroy the session
        header("location: signin.php?timeout=true");
        exit;
    }
}

// Update the last activity time
$_SESSION['last_activity'] = time();


// Store user's first and last name in variables for display
$userName = htmlspecialchars($_SESSION["fName"] . " " . $_SESSION["lName"]);
$user_id = $_SESSION["id"];

// Assuming role is stored in session as 'role'
$user_role = $_SESSION["role"];

// Fetch unread messages count
$stmt = $db->prepare("SELECT COUNT(*) FROM messages WHERE receiver_id = :user_id AND is_read = 0");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$unreadCount = $stmt->fetchColumn();

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="lad.css">
    <link rel="stylesheet" href="lad2.css">
    
    <!-- Vendor CSS -->
    <link href="lib/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="lib/typicons.font/typicons.css" rel="stylesheet">
    <link href="lib/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="lib/select2/css/select2.min.css" rel="stylesheet">
    <style>
      .az-content-header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
      }
    </style>
</head>

<div class="az-header">
  <div class="container">
    <div class="az-header-left">
      <a href="index.php" class="az-logo"><span></span>Legis360</a>
      <a href="index.php" class="az-logo">
    <span></span>
    <!-- <img src="uploads/lad_logo.png" alt="LAD Logo"> -->
</a>
      <a href="" id="azMenuShow" class="az-header-menu-icon d-lg-none"><span></span></a>
    </div><!-- az-header-left -->

    <div class="az-header-menu">
      <ul class="nav">
        <li class="nav-item show">
          <a href="dashboard.php" class="nav-link"><i class="typcn typcn-chart-area-outline"></i> Home</a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link with-sub"><i class="typcn typcn-flash"></i> Chamber</a>
          <nav class="az-menu-sub">
            <a href="sendash.php" class="nav-link">Senate</a>
            <a href="hordash.php" class="nav-link">House of Reps</a>
            <a href="statedash.php" class="nav-link">State House of Assembly</a>
          </nav>
        </li>
        <li class="nav-item">
          <a href="committee.php" class="nav-link"><i class="typcn typcn-group-outline"></i> Committees</a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link with-sub"><i class="typcn typcn-document"></i> Resources</a>
          <nav class="az-menu-sub">
            <a href="billdash.php" class="nav-link">Bills</a>
            <a href="motiondash.php" class="nav-link">Motions</a>
            <a href="petitiondash.php" class="nav-link">Petitions</a>
            <a href="votes.php" class="nav-link">Votes and Proceedings</a>
            <a href="order-paper.php" class="nav-link">Order Paper</a>
            <a href="notice-paper.php" class="nav-link">Notice Paper</a>
          </nav>
        </li>
        <li class="nav-item">
          <a href="analyzer.php" class="nav-link"><i class="typcn typcn-chart-bar-outline"></i> Bill Analyser</a>
        </li>
        <?php if ($user_role === 'admin'): ?>
        <li class="nav-item">
          <a href="" class="nav-link with-sub"><i class="typcn typcn-cog-outline"></i> Admin Menu</a>
          <nav class="az-menu-sub">
            <a href="add_legislator.php" class="nav-link">Add Legislator</a>
            <a href="archive_legislator.php" class="nav-link">Replace Legislator</a>
            <a href="add_bill.php" class="nav-link">Add Bill</a>
            <a href="add_committee.php" class="nav-link">Add Committee</a>
            <a href="defection.php" class="nav-link">Log Defection</a>
            <a href="admin_messages.php" class="nav-link">Message Board</a>
          </nav>
        </li>
        <?php endif; ?>
      </ul>
    </div><!-- az-header-menu -->

    <div class="az-header-right">
      <div class="az-header-message">
        <a href="user_inbox.php"><i class="typcn typcn-messages"></i>
        <?php if ($unreadCount > 0): ?>
            <span class="badge badge-danger position-absolute top-0 start-100 translate-middle">
              <?= htmlspecialchars($unreadCount) ?>
            </span>
          <?php endif; ?>
        </a>
      </div>

      <div class="dropdown az-profile-menu">
        <a href="#" class="az-img-user"><img src="uploads/avatar.webp" alt=""></a>
        <div class="dropdown-menu">
          <div class="az-header-profile">
            <div class="az-img-user">
              <img src="uploads/avatar.webp" alt="">
            </div><!-- az-img-user -->
            <h6><?= $userName ?></h6>
          </div><!-- az-header-profile -->

          <a href="profile.php?id=<?= urlencode($user_id); ?>" class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a>
          <a href="edit-profile.php" class="dropdown-item"><i class="typcn typcn-edit"></i> Edit Profile</a>
          <a href="activity-logs.php" class="dropdown-item"><i class="typcn typcn-time"></i> Activity Logs</a>
          <a href="logout.php" class="dropdown-item"><i class="typcn typcn-power-outline"></i> Sign Out</a>
        </div><!-- dropdown-menu -->
      </div><!-- az-profile-menu -->
    </div><!-- az-header-right -->
  </div><!-- container -->
</div><!-- az-header -->
<script src="lib/jquery/jquery.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="lib/ionicons/ionicons.js"></script>
<script src="lib/jquery.flot/jquery.flot.js"></script>
<script src="lib/chart.js/Chart.bundle.min.js"></script>
<script src="js/azia.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> 
