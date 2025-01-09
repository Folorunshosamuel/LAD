<?php

// Database connection
include 'db_connect.php'; // Make sure this path matches your connection file

// Initialize an empty message variable
$successMessage = '';
$failedMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $checkEmailQuery = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $checkEmailQuery->execute([$email]);
    $emailExists = $checkEmailQuery->fetchColumn() > 0;

    if ($emailExists) {
        $failedMessage = "Email is already registered. Please use a different email or sign in.";
    } else {
        // Hash and salt the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $insertQuery = $db->prepare("INSERT INTO users (fName, lName, role, email, password) VALUES (?, ?, ?, ?, ?)");
        $insertQuery->execute([$fName, $lName, $role, $email, $hashedPassword]);

        // Set the success message to display in the HTML
        $successMessage = "Account created successfully! <br> Click on Sign In to login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sign Up - Legislative Analysis Dashboard</title>

    <!-- CSS Libraries -->
    <link href="lib/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="lib/typicons.font/typicons.css" rel="stylesheet">
    <link rel="stylesheet" href="lad.css">
</head>
<body class="az-body">

<div class="az-signup-wrapper">
    <div class="az-column-signup-left">
        <div>
            <div class="az-logo">
                <img src="uploads/lad_logo.svg" alt="logo">
            </div>
            <h5>Legislative Analysis Dashboard</h5>
            <p>Legislative Analysis Dashboard by Yiaga Africa's  Centre for Legislative Engagement is Nigeria’s foremost independent parliamentary monitoring tool and policy think tank that bridges the gap between people and parliament.</p>
            <a href="#" class="btn btn-outline-indigo">Learn More</a>
        </div>
    </div><!-- az-column-signup-left -->

    <div class="az-column-signup">
        <h1 class="az-logo">L<span>A</span>D</h1>
        <div class="az-signup-header">
            <h2>Get Started</h2>
            <h4>It's free to signup and only takes a minute.</h4>

            <!-- Display success or error message -->
            <?php if ($successMessage): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>
            <?php if ($failedMessage): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($failedMessage) ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Firstname</label>
                    <input type="text" name="fName" required class="form-control" placeholder="Enter your firstname">
                </div>
                <div class="form-group">
                    <label>Lastname</label>
                    <input type="text" name="lName" required class="form-control" placeholder="Enter your lastname">
                </div>

                <div class="form-group">
                    <label for="role">Occupation</label>
                    <select name="role" id="role" required class="form-control">
                    <option value="">Select occupation</option>
                    <option value="Student">Student</option>
                        <option value="Researcher">Researcher</option>
                        <option value="Developmental Worker">Developmental Worker</option>
                        <option value="Citizen">Other</option>
                    </select>
                </div>
            
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required class="form-control" placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control" placeholder="Enter your password">
                </div>
                <button type="submit" class="btn btn-az-primary btn-block">Create Account</button>
            </form>
        </div><!-- az-signup-header -->

        <div class="az-signup-footer">
            <p>Already have an account? <a href="signin.php">Sign In</a></p>
        </div><!-- az-signup-footer -->
    </div><!-- az-column-signup -->
</div><!-- az-signup-wrapper -->

<script src="lib/jquery/jquery.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="lib/ionicons/ionicons.js"></script>
<script src="js/azia.js"></script>
</body>
</html>
