<?php
require 'db_connect.php';

$id = $_GET['id'] ?? null;

try {
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM pets WHERE id = ?");
        $stmt->execute([$id]);
        $pet = $stmt->fetch();
        echo json_encode(['success' => true, 'data' => $pet]);
    } else {
        $stmt = $pdo->query("SELECT * FROM pets");
        $pets = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $pets]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching pets']);
}
?>