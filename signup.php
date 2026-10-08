<?php
// api/signup.php
require_once 'db_connect.php';

$in = jsonInput();
$name     = trim($in['name']     ?? '');
$email    = trim($in['email']    ?? '');
$phone    = trim($in['phone']    ?? '');
$password = $in['password']      ?? '';
$confirm  = $in['confirm']       ?? '';

if ($name === '' || $email === '' || $password === '')
    respond(false, 'Missing fields');
if ($password !== $confirm)
    respond(false, 'Passwords do not match');
if (strlen($password) < 6)
    respond(false, 'Password must be at least 6 characters');
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    respond(false, 'Invalid email');

try {
    $chk = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $chk->execute([':email' => $email]);
    if ($chk->fetch()) respond(false, 'Email already registered');

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, role, phone)
        VALUES (:name, :email, :password, 'client', :phone)
    ");
    $stmt->execute([
        ':name'     => $name,
        ':email'    => $email,
        ':password' => $hash,
        ':phone'    => $phone,
    ]);

    $id = (int)$pdo->lastInsertId();
    echo json_encode([
        'success'   => true,
        'id'        => $id,
        'client_id' => 'client' . $id,
    ]);
} catch (PDOException $e) {
    respond(false, $e->getMessage(), 500);
}