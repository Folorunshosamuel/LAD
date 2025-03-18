<?php
header("Content-Type: application/json");
include 'db_connect.php';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getStateAssemblyMembers($db);
        break;
    case 'POST':
        addStateAssemblyMember($db);
        break;
    case 'PUT':
        updateStateAssemblyMember($db);
        break;
    case 'DELETE':
        deleteStateAssemblyMember($db);
        break;
    default:
        echo json_encode(["error" => "Invalid request method"]);
}

// Fetch State Assembly Members with filters
function getStateAssemblyMembers($db) {
    $state = $_GET['state'] ?? '';
    $party = $_GET['party'] ?? '';
    $gender = $_GET['gender'] ?? '';

    $query = "SELECT * FROM state_assembly_members WHERE 1";
    $params = [];

    if (!empty($state)) {
        $query .= " AND state = ?";
        $params[] = $state;
    }
    if (!empty($party)) {
        $query .= " AND party = ?";
        $params[] = $party;
    }
    if (!empty($gender)) {
        $query .= " AND gender = ?";
        $params[] = $gender;
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($members);
}

// Add a new state assembly member
function addStateAssemblyMember($db) {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['name'], $data['dob'], $data['gender'], $data['state'], $data['party'])) {
        echo json_encode(["error" => "Missing required fields"]);
        return;
    }

    $query = "INSERT INTO state_assembly_members (name, dob, gender, state, constituency, party, image)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $success = $stmt->execute([
        $data['name'], $data['dob'], $data['gender'], 
        $data['state'], $data['constituency'], $data['party'], 
        $data['image'] ?? null
    ]);

    echo json_encode(["success" => $success]);
}

// Update state assembly member
function updateStateAssemblyMember($db) {
    parse_str(file_get_contents("php://input"), $data);
    if (!isset($data['id'])) {
        echo json_encode(["error" => "Missing member ID"]);
        return;
    }

    $query = "UPDATE state_assembly_members SET name = ?, dob = ?, gender = ?, state = ?, constituency = ?, party = ?, image = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $success = $stmt->execute([
        $data['name'], $data['dob'], $data['gender'], 
        $data['state'], $data['constituency'], $data['party'], 
        $data['image'] ?? null, $data['id']
    ]);

    echo json_encode(["success" => $success]);
}

// Delete a state assembly member
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
