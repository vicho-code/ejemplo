<?php
// functions.php - helpers para autenticación y permisos
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "conexion.php";

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function current_user() {
    if (!is_logged_in()) return null;
    global $conn;
    $id = intval($_SESSION['user_id']);
    $stmt = $conn->prepare("SELECT id, username, email, role FROM usuarios WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();
    return $user ?: null;
}

function is_admin() {
    $user = current_user();
    return $user && isset($user['role']) && $user['role'] === 'admin';
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function require_admin() {
    if (!is_logged_in() || !is_admin()) {
        header('Location: index.php');
        exit;
    }
}

// Marca actividad del usuario (last_active)
function touch_activity($user_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE usuarios SET last_active = NOW() WHERE id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->close();
}
?>