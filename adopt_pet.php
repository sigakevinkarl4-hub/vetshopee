<?php
// api/adopt_pet.php
require_once 'db_connect.php';

$in = jsonInput();
$petId  = (int)($in['pet_id']  ?? 0);
$userId = (int)($in['user_id'] ?? 0);

if ($petId <= 0)  respond(false, 'Invalid pet');
if ($userId <= 0) respond(false, 'Invalid user');

try {
    $stmt = $pdo->prepare("
        UPDATE pets
        SET adopted = 1, owner_id = :uid
        WHERE id = :pid AND adopted = 0
    ");
    $stmt->execute([':uid' => $userId, ':pid' => $petId]);

    if ($stmt->rowCount() === 0)
        respond(false, 'Pet already adopted or not found');

    echo json_encode(['success' => true, 'message' => 'Adopted!']);
} catch (PDOException $e) {
    respond(false, $e->getMessage(), 500);
}