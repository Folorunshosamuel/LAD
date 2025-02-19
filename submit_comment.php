<?php
// submit_comment.php

session_start();
require 'db_connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['post_id']) || !isset($data['content'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$post_id = intval($data['post_id']);
$content = trim($data['content']);
if ($post_id <= 0 || empty($content)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// Assume logged-in user; replace with actual session user ID
$user_id = $_SESSION["id"];

$stmt = $db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
if ($stmt->execute([$post_id, $user_id, $content])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to submit comment']);
}
?>
