<?php
// create_post.php

session_start();
require 'db_connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['title']) || !isset($data['content']) || !isset($data['category_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$title = trim($data['title']);
$content = trim($data['content']);
$category_id = intval($data['category_id']);
if (empty($title) || empty($content) || $category_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

// Assume logged-in user; replace with actual session user ID
$user_id = $_SESSION["id"];

$stmt = $db->prepare("INSERT INTO posts (user_id, category_id, title, content) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$user_id, $category_id, $title, $content])) {
    echo json_encode(['success' => true, 'post_id' => $db->lastInsertId()]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to create post']);
}
?>
