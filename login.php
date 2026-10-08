<?php
// api/login.php
require_once 'db_connect.php';

$in = jsonInput();
$email    = trim($in['email']    ?? '');
$password = $in['password']      ?? '';
$role     = $in['role']          ?? 'client';

if ($email === '' || $password === '')
    respond(false, 'Email and password required');

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND role = :role LIMIT 1");
    $stmt->execute([':email' => $email, ':role' => $role]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password']))
        respond(false, 'Invalid credentials', 401);

    echo json_encode([
        'success' => true,
        'user' => [
            'id'       => (int)$user['id'],
            'name'     => $user['name'],
            'email'    => $user['email'],
            'role'     => $user['role'],
            'phone'    => $user['phone'],
            'clientId' => 'client' . $user['id'],
        ]
    ]);
} catch (PDOException $e) {
    respond(false, $e->getMessage(), 500);
}