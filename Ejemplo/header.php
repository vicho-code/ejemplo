<?php
// header.php - menú superior con icono dinámico
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'functions.php';
$user = current_user();
?>
<header style="padding:10px;border-bottom:1px solid #ccc;margin-bottom:20px">
    <a href="index.php">Inicio</a> |
    <?php if ($user): ?>
        <a href="perfil.php">Perfil (<?php echo htmlspecialchars($user['username']); ?>)</a> |
        <?php if ($user['role'] === 'admin'): ?><a href="admin.php">Panel Admin</a> |<?php endif; ?>
        <a href="logout.php">Cerrar sesión</a>
        <span style="float:right">
            <img src="img/pngwing.com.png" alt="user" style="height:32px;vertical-align:middle">
        </span>
    <?php else: ?>
        <a href="login.php">Entrar</a> |
        <a href="register.php">Registrarse</a>
        <span style="float:right">
            <img src="img/pngwing.com.png" alt="guest" style="height:32px;opacity:0.6;vertical-align:middle">
        </span>
    <?php endif; ?>
</header>