<?php
session_start();
require 'db_connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['post_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$post_id = intval($data['post_id']);
if ($post_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid post ID']);
    exit;
}

$user_id = $_SESSION["id"]; // Ensure session is started and user is logged in

// Check if user already liked the post
$stmt = $db->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
$stmt->execute([$user_id, $post_id]);
$like = $stmt->fetch();

if ($like) {
    // Unlike the post
    $stmt = $db->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
    $stmt->execute([$user_id, $post_id]);
    $action = 'unlike';
} else {
    // Like the post
    $stmt = $db->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $post_id]);
    $action = 'like';
}

// Re-fetch the updated like count
$query = $db->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE post_id = ?");
$query->execute([$post_id]);
$like_count = $query->fetchColumn();

// Return the updated data as JSON
echo json_encode(['success' => true, 'like_count' => $like_count, 'action' => $action]);
?>