<?php
/* // Initialize the session
session_start();



// Destroy the session
session_destroy();

// Redirect to sign-in page
header("location: signin.php");
exit; */


session_start();
include 'db_connect.php';
include 'utils.php';

// Log the logout activity
logActivity($db, $_SESSION['id'], 'Logged out');

// Unset all session variables
$_SESSION = [];

// Destroy session
session_destroy();
header('Location: signin.php');
exit;

?>
