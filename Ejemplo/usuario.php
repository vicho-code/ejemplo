<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Usuario</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>Bienvenido <?php echo $_SESSION['username']; ?> </header>
  <section>
    <h2>Tu Perfil</h2>
    <p><strong>Usuario:</strong> <?php echo $_SESSION['username']; ?></p>
    <p><strong>Email:</strong> (se puede consultar en BD si quieres mostrarlo)</p>
    <p><strong>Rol:</strong> <?php echo $_SESSION['role']; ?></p>
  </section>
  <footer>© 2025 La Casa del Pan - Panel de Usuario</footer>
</body>
</html>
