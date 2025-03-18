<?php
include 'db_connect.php';

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
    
    // Iterate over each legislator to insert into users table
    foreach ($legislators as $legislator) {
        // Check if email is empty and set a default if needed
        $email = !empty($legislator['email']) ? $legislator['email'] : "default_{$legislator['id']}@example.com";

        // Split the legislator's name into first and last name
        $names = explode(' ', $legislator['name'], 2);
        $fName = $names[0];
        $lName = isset($names[1]) ? $names[1] : '';

        // Set the default role and a hashed password
        $role = 'legislator';
        $password = password_hash('defaultPassword123', PASSWORD_DEFAULT);

        // Insert new user account into users table
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

    // Commit the transaction
    $db->commit();
    echo "User accounts for legislators created successfully.";
} catch (PDOException $e) {
    // Rollback the transaction if an error occurs
    $db->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
