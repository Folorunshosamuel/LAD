<?php
// Include database connection
include 'db_connect.php';

// Set records per page
$recordsPerPage = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Handle search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare SQL query to fetch only Senate members with search and pagination
$query = "SELECT id, name, position, constituency, state, image FROM legislators WHERE chamber LIKE '%Senate%' AND is_archived = FALSE"; // Filter for Senate members only

if ($search) {
    $query .= " AND (name LIKE :search OR constituency LIKE :search)";
}

$query .= " LIMIT :limit OFFSET :offset";

// Prepare and bind values
$stmt = $db->prepare($query);

if ($search) {
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$senMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch total records for pagination
$totalQuery = "SELECT COUNT(*) 
               FROM legislators 
               WHERE chamber LIKE '%Senate%' AND is_archived = FALSE";

if ($search) {
    $totalQuery .= " AND (name LIKE :search OR constituency LIKE :search)";
}

$totalStmt = $db->prepare($totalQuery);

if ($search) {
    $totalStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}

$totalStmt->execute();
$totalRecords = $totalStmt->fetchColumn();
$totalPages = ceil($totalRecords / $recordsPerPage);

// Return JSON response
echo json_encode([
    'senMembers' => $senMembers,
    'totalPages' => $totalPages
]);
?>
