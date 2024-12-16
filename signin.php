<?php
// Initialize the session
session_start();

// Redirect to dashboard if the user is already logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

// Include database connection
include 'db_connect.php';

// Initialize variables
$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, fName, lName, email, password FROM users WHERE email = :email";
        
        if ($stmt = $db->prepare($sql)) {
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Check if email exists, if yes then verify password
                if ($stmt->rowCount() == 1) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $id = $row["id"];
                    $stored_password = $row["password"];
                    $fName = $row["fName"];
                    $lName = $row["lName"];

                    if (password_verify($password, $stored_password)) {
                        // Password is correct, start a new session
                        session_start();
                        
                        // Store session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["email"] = $email;
                        $_SESSION["fName"] = $fName;
                        $_SESSION["lName"] = $lName;
                        
                        // Redirect user to dashboard
                        header("location: index.php");
                        exit;
                    } else {
                        // Password is not valid
                        $login_err = "Invalid email or password.";
                    }
                } else {
                    // Email doesn't exist
                    $login_err = "Invalid email or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            unset($stmt);
        }
    }
    
    unset($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Legislative Analysis Dashboard</title>

    <!-- Vendor CSS -->
    <link href="lib/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="lib/typicons.font/typicons.css" rel="stylesheet">
    <link rel="stylesheet" href="lad.css">
</head>
<body class="az-body">

<div class="az-signin-wrapper">
    <div class="az-card-signin">
        <div class="az-logo">
            <img src="uploads/lad_logo.svg" alt="logo">
        </div>
        <div class="az-signin-header">
            <h2>Welcome back!</h2>
            <h4>Please sign in to continue</h4>

            <!-- Display error message if login fails -->
            <?php if (!empty($login_err)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($login_err); ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" placeholder="Enter your email" value="<?= htmlspecialchars($email); ?>">
                    <span class="text-danger"><?= htmlspecialchars($email_err); ?></span>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password">
                    <span class="text-danger"><?= htmlspecialchars($password_err); ?></span>
                </div>
                <button type="submit" class="btn btn-az-primary btn-block">Sign In</button>
            </form>
        </div><!-- az-signin-header -->
        
        <div class="az-signin-footer">
            <p><a href="#">Forgot password?</a></p>
            <p>Don't have an account? <a href="signup.php">Create an Account</a></p>
        </div><!-- az-signin-footer -->
    </div><!-- az-card-signin -->
</div><!-- az-signin-wrapper -->

<script src="lib/jquery/jquery.min.js"></script>
<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="lib/ionicons/ionicons.js"></script>
<script src="js/azia.js"></script>
</body>
</html>
