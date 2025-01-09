<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 300);
ini_set('memory_limit', '256M');

include 'db_connect.php';

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
    // Fetch legislators without a corresponding user account in batches
    $batchSize = 50; // Adjust batch size as needed
    $offset = 0;
    $csvFile = 'generated_passwords.csv';
    $fileHandle = fopen($csvFile, 'w');
    if (!$fileHandle) {
        die("Unable to create or write to the file. Check permissions.");
    }
    fputcsv($fileHandle, ["Legislator ID", "Name", "Email", "Generated Password"]);

    do {
        $sql = "SELECT id, name, email FROM legislators WHERE id NOT IN (SELECT id FROM users) LIMIT :batchSize OFFSET :offset";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':batchSize', $batchSize, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $legislators = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($legislators)) {
            break;
        }

        // Begin transaction for each batch
        $db->beginTransaction();

        foreach ($legislators as $legislator) {
            $legislatorId = $legislator['id'];
            $name = $legislator['name'];
            $email = $legislator['email'] ?: "{$legislatorId}@example.com";
            $password = generatePassword();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (id, fName, lName, role, email, password)
                    VALUES (:id, :fName, :lName, :role, :email, :password)";
            $stmt = $db->prepare($sql);
            $names = explode(' ', $name, 2);
            $stmt->execute([
                ':id' => $legislatorId,
                ':fName' => $names[0] ?? '',
                ':lName' => $names[1] ?? '',
                ':role' => 'legislator',
                ':email' => $email,
                ':password' => $hashedPassword
            ]);

            fputcsv($fileHandle, [$legislatorId, $name, $email, $password]);
        }

        $db->commit();
        $offset += $batchSize;

    } while (count($legislators) === $batchSize);

    fclose($fileHandle);
    echo "User accounts created successfully. Passwords saved to: $csvFile";
} catch (PDOException $e) {
    $db->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
