<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['email']) || !isset($_SESSION['foto'])) {
    header("Location: login.php");
    exit();
}

// Obtener datos de sesión
$email = $_SESSION['email'];
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario sin nombre';
$foto = $_SESSION['foto'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding-top: 50px;
        }
        .perfil {
            background: #fff;
            padding: 30px;
            display: inline-block;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .perfil img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .perfil h2, .perfil p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="perfil">
        <img src="<?php echo $foto; ?>" alt="Foto de perfil">
        <h2><?php echo htmlspecialchars($usuario); ?></h2>
        <p><?php echo htmlspecialchars($email); ?></p>
        <a href="logout.php">Cerrar sesión</a>
    </div>
</body>
</html>
