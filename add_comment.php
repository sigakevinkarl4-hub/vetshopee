<?php
require 'db_connect.php';

$data = json_decode(file_get_contents("php://input"));

if (empty($data->user_id) || empty($data->post_content)) {
    echo json_encode(['success' => false, 'message' => 'User ID and content required']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_content) VALUES (?, ?)");
    $stmt->execute([$data->user_id, $data->post_content]);
    echo json_encode(['success' => true, 'message' => 'Comment added']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error adding comment']);
}
?>