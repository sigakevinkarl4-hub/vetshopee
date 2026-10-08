<?php
// api/add_pet.php
require_once 'db_connect.php';

$in = jsonInput();

$name       = trim($in['name']       ?? '');
$species    = trim($in['species']    ?? 'Other');
$breed      = trim($in['breed']      ?? 'Unknown');
$age        = (int)   ($in['age']       ?? 1);
$avatar     = trim($in['avatar']     ?? '🐾');
$vaccinated = !empty($in['vaccinated']) ? 1 : 0;
$price      = (float) ($in['price']     ?? 0);

if ($name === '')          respond(false, 'Pet name is required');
if ($age < 0)   $age = 0;
if ($age > 100) $age = 100;
if ($price < 0) $price = 0;
if ($avatar === '') $avatar = '🐾';
if (mb_strlen($avatar) > 4) $avatar = mb_substr($avatar, 0, 4);

try {
    $stmt = $pdo->prepare("
        INSERT INTO pets (name, species, breed, age, avatar, vaccinated, price)
        VALUES (:name, :species, :breed, :age, :avatar, :vaccinated, :price)
    ");
    $stmt->execute([
        ':name'       => $name,
        ':species'    => $species,
        ':breed'      => $breed,
        ':age'        => $age,
        ':avatar'     => $avatar,
        ':vaccinated' => $vaccinated,
        ':price'      => $price,
    ]);

    $newId = (int)$pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Pet registered successfully',
        'id'      => $newId,
        'pet'     => [
            'id'         => $newId,
            'name'       => $name,
            'species'    => $species,
            'breed'      => $breed,
            'age'        => $age,
            'avatar'     => $avatar,
            'vaccinated' => (bool)$vaccinated,
            'adopted'    => false,
            'owner'      => null,
            'price'      => $price,
        ],
    ]);
} catch (PDOException $e) {
    respond(false, $e->getMessage(), 500);
}