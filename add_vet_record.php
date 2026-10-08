<?php
// api/add_vet_record.php
require_once 'db_connect.php';

$in = jsonInput();

$petId     = (int)($in['pet_id']     ?? 0);
$date      = trim($in['date']        ?? '');
$diagnosis = trim($in['diagnosis']   ?? '');
$treatment = trim($in['treatment']   ?? '');
$vet       = trim($in['vet']         ?? 'Dr. Cruz');

if ($petId <= 0)          respond(false, 'Invalid pet');
if ($date === '')         respond(false, 'Date is required');
if ($diagnosis === '')    respond(false, 'Diagnosis is required');
if ($treatment === '')    respond(false, 'Treatment is required');

try {
    $chk = $pdo->prepare("SELECT name FROM pets WHERE id = :id");
    $chk->execute([':id' => $petId]);
    $pet = $chk->fetch();
    if (!$pet) respond(false, 'Pet not found');

    $stmt = $pdo->prepare("
        INSERT INTO vet_records (pet_id, diagnosis, treatment, vet, record_date)
        VALUES (:pet_id, :diagnosis, :treatment, :vet, :date)
    ");
    $stmt->execute([
        ':pet_id'    => $petId,
        ':diagnosis' => $diagnosis,
        ':treatment' => $treatment,
        ':vet'       => $vet,
        ':date'      => $date,
    ]);

    echo json_encode([
        'success'  => true,
        'id'       => (int)$pdo->lastInsertId(),
        'petName'  => $pet['name'],
        'message'  => 'Vet record added',
    ]);
} catch (PDOException $e) {
    respond(false, $e->getMessage(), 500);
}