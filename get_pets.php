<?php
// api/get_pets.php
require_once 'db_connect.php';

try {
    $stmt = $pdo->query("
        SELECT p.id, p.name, p.species, p.breed, p.age, p.avatar,
               p.vaccinated, p.adopted, p.price, p.owner_id,
               u.name AS owner_name
        FROM pets p
        LEFT JOIN users u ON u.id = p.owner_id
        ORDER BY p.id ASC
    ");
    $rows = $stmt->fetchAll();

    foreach ($rows as &$r) {
        $r['id']         = (int)$r['id'];
        $r['age']        = (int)$r['age'];
        $r['price']      = (float)$r['price'];
        $r['vaccinated'] = (bool)$r['vaccinated'];
        $r['adopted']    = (bool)$r['adopted'];
        $r['owner']      = $r['owner_id'] ? 'client' . $r['owner_id'] : null;
        unset($r['owner_id']);
    }

    echo json_encode(['success' => true, 'data' => $rows]);
} catch (PDOException $e) {
    respond(false, $e->getMessage(), 500);
}