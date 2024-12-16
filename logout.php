<?php
// Initialize the session
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to sign-in page
header("location: signin.php");
exit;
