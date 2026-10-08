<?php
// api/get_vet_records.php
require_once 'db_connect.php';

try {
    $stmt = $pdo->query("
        SELECT vr.id, vr.pet_id, p.name AS petName,
               vr.diagnosis, vr.treatment, vr.vet,
               vr.record_date AS date
        FROM vet_records vr
        JOIN pets p ON p.id = vr.pet_id
        ORDER BY vr.record_date DESC
    ");
    $rows = $stmt->fetchAll();
    foreach ($rows as &$r) {
        $r['id']    = (int)$r['id'];
        $r['petId'] = (int)$r['pet_id'];
        unset($r['pet_id']);
    }
    echo json_encode(['success' => true, 'data' => $rows]);
} catch (PDOException $e) {
    respond(false, $e->getMessage(), 500);
}