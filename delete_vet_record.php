<?php
// api/delete_vet_record.php
require_once 'db_connect.php';

$in = jsonInput();
$id = (int)($in['id'] ?? 0);
if ($id <= 0) respond(false, 'Invalid record ID');

try {
    $stmt = $pdo->prepare("DELETE FROM vet_records WHERE id = :id");
    $stmt->execute([':id' => $id]);
    echo json_encode(['success' => $stmt->rowCount() > 0]);
} catch (PDOException $e) {
    respond(false, $e->getMessage(), 500);
}