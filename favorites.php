<?php
require 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Add to favorites
    $data = json_decode(file_get_contents("php://input"));
    try {
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, item_id, item_type) VALUES (?, ?, ?)");
        $stmt->execute([$data->user_id, $data->item_id, $data->item_type]);
        echo json_encode(['success' => true, 'message' => 'Added to favorites']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error adding favorite']);
    }
} else if ($method === 'GET') {
    // Get user favorites
    $user_id = $_GET['user_id'] ?? null;
    $stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ?");
    $stmt->execute([$user_id]);
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
}
?>