<?php
// api/test.php
require_once 'db_connect.php';

$out = [
    'php_version'   => PHP_VERSION,
    'pdo_mysql'     => extension_loaded('pdo_mysql'),
    'db_connected'  => isset($pdo),
    'pets_count'    => null,
    'tables'        => [],
    'json_input_fn' => function_exists('jsonInput'),
];

try {
    $out['tables']     = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $out['pets_count'] = (int)$pdo->query("SELECT COUNT(*) FROM pets")->fetchColumn();
    $out['users_count']= (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
} catch (Exception $e) {
    $out['error'] = $e->getMessage();
}

echo json_encode($out, JSON_PRETTY_PRINT);