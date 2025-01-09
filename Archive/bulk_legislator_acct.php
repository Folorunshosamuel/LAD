<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 900);
ini_set('memory_limit', '2000M');

include 'db_connect.php';

// Include a library for generating random passwords
function generatePassword($length = 12) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = '';
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, $max)];
    }
    return $password;
}

try {
    // Fetch legislators without a corresponding user account
    $sql = "SELECT id, name, email FROM legislators WHERE id NOT IN (SELECT id FROM users)";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $legislators = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($legislators)) {
        die("No legislators to process.");
    }

    // Create CSV file
    $csvFile = 'generated_passwords.csv';
    $fileHandle = fopen($csvFile, 'w');
    if (!$fileHandle) {
        die("Unable to create or write to the file. Check permissions.");
    }
    fputcsv($fileHandle, ["Legislator ID", "Name", "Email", "Generated Password"]);

    // Begin transaction
    $db->beginTransaction();

    foreach ($legislators as $legislator) {
        $legislatorId = $legislator['id'];
        $name = $legislator['name'];
        $email = $legislator['email'] ?: "legislator_{$legislatorId}@lad.org"; // Default email if none exists
        $password = generatePassword(); // Generate random password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        // Insert the user into the users table
        $sql = "INSERT INTO users (id, fName, lName, role, email, password)
                VALUES (:id, :fName, :lName, :role, :email, :password)";
        $stmt = $db->prepare($sql);
        $names = explode(' ', $name, 2); // Split name into first and last
        $stmt->execute([
            ':id' => $legislatorId,
            ':fName' => $names[0] ?? '',
            ':lName' => $names[1] ?? '',
            ':role' => 'legislator',
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        // Write to CSV
        fputcsv($fileHandle, [$legislatorId, $name, $email, $password]);
    }

    // Commit transaction
    $db->commit();
    fclose($fileHandle);

    echo "User accounts created successfully. Passwords saved to: $csvFile";
} catch (PDOException $e) {
    // Rollback transaction if an error occurs
    $db->rollBack();
    echo "Error: " . $e->getMessage();
}
?>

