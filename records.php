<?php
require 'db_connect.php';

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'User ID required']);
    exit;
}

try {
    // Get all pets for user
    $stmt = $pdo->prepare("SELECT id, name FROM pets WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $pets = $stmt->fetchAll();
    
    // In a real app, you might loop through pets here to get their medical/vax records,
    // or write a complex SQL JOIN. For simplicity, we just return the pets.
    
    echo json_encode(['success' => true, 'message' => 'Records fetched', 'pets' => $pets]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching records']);
}
?>