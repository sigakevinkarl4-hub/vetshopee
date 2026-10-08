<?php
require 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Create appointment
    $data = json_decode(file_get_contents("php://input"));
    
    if (empty($data->user_id) || empty($data->pet_id) || empty($data->appointment_date)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO appointments (user_id, pet_id, appointment_date, reason) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data->user_id, $data->pet_id, $data->appointment_date, $data->reason ?? '']);
        echo json_encode(['success' => true, 'message' => 'Appointment booked']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to book appointment']);
    }
} else if ($method === 'GET') {
    // Fetch appointments for a user
    $user_id = $_GET['user_id'] ?? null;
    try {
        if ($user_id) {
            $stmt = $pdo->prepare("SELECT a.*, p.name as pet_name FROM appointments a JOIN pets p ON a.pet_id = p.id WHERE a.user_id = ?");
            $stmt->execute([$user_id]);
        } else {
            $stmt = $pdo->query("SELECT * FROM appointments");
        }
        $appointments = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $appointments]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching appointments']);
    }
}
?>