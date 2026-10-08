<?php
require 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    try {
        $stmt = $pdo->prepare("INSERT INTO vaccinations (pet_id, vaccine_name, date_administered, next_due_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data->pet_id, $data->vaccine_name, $data->date_administered, $data->next_due_date]);
        echo json_encode(['success' => true, 'message' => 'Vaccination recorded']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error recording vaccination']);
    }
} else {
    $pet_id = $_GET['pet_id'] ?? null;
    $stmt = $pdo->prepare("SELECT * FROM vaccinations WHERE pet_id = ?");
    $stmt->execute([$pet_id]);
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
}
?>