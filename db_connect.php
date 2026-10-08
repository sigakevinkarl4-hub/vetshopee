<?php
// ============================================================
//  api/db_connect.php
//  Central PDO connection + shared helpers
// ============================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$host    = 'localhost';
$db      = 'vetshoppe';
$user    = 'root';
$pass    = '';                 // XAMPP default = empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'DB connection failed: ' . $e->getMessage()
    ]);
    exit;
}

/**
 * Read + decode JSON body from a POST request.
 * Returns an associative array (empty if none).
 */
function jsonInput(): array {
    $raw  = file_get_contents('php://input');
    if ($raw === false || $raw === '') return [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/**
 * Send a JSON response and stop.
 */
function respond(bool $success, $dataOrError = null, int $code = 200): void {
    http_response_code($code);
    if ($success) {
        echo json_encode(is_array($dataOrError) ? $dataOrError : ['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => (string)$dataOrError]);
    }
    exit;
}