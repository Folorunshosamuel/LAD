<?php
include 'db_connect.php';
set_time_limit(300); // Sets the maximum execution time to 5 minutes

// Enable error mode for PDO
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Begin a transaction to handle multiple inserts safely
$db->beginTransaction();

try {
    // Fetch legislators without a corresponding user account
    $sql = "SELECT id, name, email FROM legislators WHERE id NOT IN (SELECT id FROM users)";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $legislators = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($legislators);

    foreach ($legislators as $legislator) {
        // Set a default email if it's empty
        $email = !empty($legislator['email']) ? $legislator['email'] : "legislator_{$legislator['id']}@lad.org";

        // Split full name into first and last name
        $names = explode(' ', $legislator['name'], 2);
        $fName = $names[0];
        $lName = isset($names[1]) ? $names[1] : '';

        // Set default values for the new user account
        $role = 'legislator';
        $password = password_hash('defaultPassword123', PASSWORD_DEFAULT);

        // Insert new user account
        $insertSql = "INSERT INTO users (id, fName, lName, role, email, password)
                      VALUES (:id, :fName, :lName, :role, :email, :password)";
        $insertStmt = $db->prepare($insertSql);
        $insertStmt->execute([
            ':id' => $legislator['id'],
            ':fName' => $fName,
            ':lName' => $lName,
            ':role' => $role,
            ':email' => $email,
            ':password' => $password
        ]);

        echo "Inserted user for legislator ID " . $legislator['id'] . "<br>";
    }

    // Commit transaction
    $db->commit();
    echo "User accounts for legislators created successfully.";
} catch (PDOException $e) {
    // Rollback transaction if an error occurs
    $db->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
