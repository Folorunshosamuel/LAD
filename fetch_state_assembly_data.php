<?php
header("Content-Type: application/json");
include 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['members'])) {
            getMembersList($db);
        } else {
            getStateWisePartyDistribution($db);
        }
        break;
    case 'POST':
        addStateAssemblyMember($db);
        break;
    case 'DELETE':
        deleteStateAssemblyMember($db);
        break;
    case 'PUT':
        updateStateAssemblyMember($db);
        break;
    default:
        echo json_encode(["error" => "Invalid request method"]);
}

// Fetch state-wise party distribution
function getStateWisePartyDistribution($db) {
    $query = "SELECT state, party, COUNT(*) AS count FROM state_assembly_members GROUP BY state, party ORDER BY state, count DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $partyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stateData = [];
    foreach ($partyData as $row) {
        $stateData[$row['state']][] = [
            'party' => $row['party'],
            'count' => (int) $row['count']
        ];
    }

    echo json_encode($stateData);
}

// Fetch members list
/* function getMembersList($db) {
    $state = $_GET['state'] ?? '';

    $query = "SELECT id, name, gender, state, constituency, party, image, position FROM state_assembly_members";
    $params = [];

    if (!empty($state)) {
        $query .= " WHERE state = ?";
        $params[] = $state;
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["data" => $members]); // Wrap in a "data" key for DataTables
} */

function getMembersList($db) {
    $state = $_GET['state'] ?? '';

    $query = "SELECT id, name, gender, state, constituency, party, image, position, dob FROM state_assembly_members";
    $params = [];

    if (!empty($state)) {
        $query .= " WHERE state = ?";
        $params[] = $state;
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate age from DOB
    foreach ($members as &$member) {
        if (!empty($member['dob'])) {
            $dob = new DateTime($member['dob']);
            $today = new DateTime();
            $member['age'] = $dob->diff($today)->y; // Calculate age
        } else {
            $member['age'] = null; // Handle missing DOB
        }
    }

    echo json_encode(["data" => $members]);
}


// Update a member
function updateStateAssemblyMember($db) {
    // If you are using x-www-form-urlencoded, parse_str is fine
    parse_str(file_get_contents("php://input"), $data);
    if (!isset($data['id'], $data['name'], $data['gender'], $data['party'], $data['state'], $data['constituency'], $data['position'])) {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        return;
    }

    $query = "UPDATE state_assembly_members SET name = ?, gender = ?, party = ?, state = ?, constituency = ?, position = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $success = $stmt->execute([
        $data['name'], 
        $data['gender'], 
        $data['party'], 
        $data['state'], 
        $data['constituency'], 
        $data['position'], 
        $data['id']
    ]);

    if ($success) {
        echo json_encode(["success" => true]);
    } else {
        // Retrieve PDO error message and return it
        $errorInfo = $stmt->errorInfo();
        echo json_encode(["success" => false, "message" => $errorInfo[2]]);
    }
}

// Delete a member
function deleteStateAssemblyMember($db) {
    parse_str(file_get_contents("php://input"), $data);
    if (!isset($data['id'])) {
        echo json_encode(["error" => "Missing member ID"]);
        return;
    }

    $query = "DELETE FROM state_assembly_members WHERE id = ?";
    $stmt = $db->prepare($query);
    $success = $stmt->execute([$data['id']]);

    echo json_encode(["success" => $success]);
}
?>
