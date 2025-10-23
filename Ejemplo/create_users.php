<?php
// create_users.php - crear usuarios precargados con password_hash
require_once 'conexion.php';

// verificar si existen
$check = $conn->query("SELECT COUNT(*) as c FROM usuarios WHERE username IN ('admin1','user1')")->fetch_assoc()['c'];
if ($check > 0) {
    echo 'Los usuarios ya existen. Borra manualmente si quieres recrearlos.';
    exit;
}

$pass_plain = '123';
$hash = password_hash($pass_plain, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO usuarios (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())');
$role = 'admin';
$u = 'admin1';
$e = 'admin1@example.com';
$stmt->bind_param('ssss', $u, $e, $hash, $role);
$stmt->execute();

$u2 = 'user1';
$e2 = 'user1@example.com';
$role2 = 'user';
$stmt->bind_param('ssss', $u2, $e2, $hash, $role2);
$stmt->execute();

echo 'Usuarios creados: admin1 / user1 (contraseña: 123)';
?>