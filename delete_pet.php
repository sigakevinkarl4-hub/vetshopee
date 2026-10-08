<?php
// api/delete_pet.php
require_once 'db_connect.php';

$in = jsonInput();
$id = (int)($in['id'] ?? 0);

if ($id <= 0) respond(false, 'Invalid pet ID');

try {
    // check existence
    $chk = $pdo->prepare("SELECT name FROM pets WHERE id = :id");
    $chk->execute([':id' => $id]);
    $pet = $chk->fetch();

    if (!$pet) respond(false, 'Pet not found');

    // vet_records, favorites, appointments cascade automatically via FK
    $del = $pdo->prepare("DELETE FROM pets WHERE id = :id");
    $del->execute([':id' => $id]);

    echo json_encode([
        'success' => true,
        'message' => 'Pet deleted successfully',
        'id'      => $id,
        'name'    => $pet['name'],
    ]);
} catch (PDOException $e) {
    respond(false, $e->getMessage(), 500);
}