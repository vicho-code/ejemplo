<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'conexion.php';

$error = '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['usuario']) ? trim($_POST['usuario']) : ''; // <-- CAMBIO
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $stmt = $conn->prepare("SELECT id, username, password, role FROM usuarios WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];

        $stmt2 = $conn->prepare("UPDATE usuarios SET last_active = NOW() WHERE id = ?");
        $stmt2->bind_param('i', $user['id']);
        $stmt2->execute();
        $stmt2->close();

        if ($user['role'] === 'admin') {
    header("Location: admin.php");
    exit;
} else {
    header("Location: usuario.php");
    exit;
}

    } else {
        $error = "❌ Usuario o contraseña incorrectos.";
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #23242a;
        }

        .box {
            position: relative;
            width: 380px;
            height: 520px;
            background: #1c1c1c;
            border-radius: 8px;
            overflow: hidden;
        }

        .box::before,
        .box::after,
        .borderLine {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 380px;
            height: 520px;
            background: linear-gradient(0deg, transparent, #45f3ff, #45f3ff, #45f3ff);
            transform-origin: bottom right;
            animation: animate 6s linear infinite;
        }

        .borderLine {
            z-index: 1;
        }

        .box form {
            position: absolute;
            inset: 4px;
            background: #28292d;
            padding: 50px 40px;
            border-radius: 8px;
            z-index: 2;
            display: flex;
            flex-direction: column;
        }

        .box form h2 {
            color: #45f3ff;
            font-weight: 500;
            text-align: center;
            letter-spacing: 0.1em;
        }

        .box form .inputBox {
            position: relative;
            width: 100%;
            margin-top: 35px;
        }

        .box form .inputBox input {
            position: relative;
            width: 100%;
            padding: 10px 0;
            background: transparent;
            border: none;
            outline: none;
            color: #fff;
            font-size: 1em;
            letter-spacing: 0.05em;
            z-index: 10;
        }

        .box form .inputBox span {
            position: absolute;
            left: 0;
            padding: 10px 0;
            pointer-events: none;
            font-size: 1em;
            color: #8f8f8f;
            letter-spacing: 0.05em;
            transition: 0.5s;
        }

        .box form .inputBox input:valid ~ span,
        .box form .inputBox input:focus ~ span {
            color: #45f3ff;
            transform: translateY(-34px);
            font-size: 0.75em;
        }

        .box form .inputBox i {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background: #45f3ff;
            border-radius: 4px;
            transition: 0.5s;
            pointer-events: none;
            z-index: 9;
        }

        .box form .inputBox input:valid ~ i,
        .box form .inputBox input:focus ~ i {
            height: 44px;
        }

        .box form input[type="submit"] {
            border: none;
            outline: none;
            padding: 11px 25px;
            background: #45f3ff;
            cursor: pointer;
            border-radius: 4px;
            font-weight: 600;
            width: 100%;
            margin-top: 40px;
        }

        .box form .message {
            margin-top: 20px;
            text-align: center;
            color: #fff;
            font-size: 0.9em;
        }

        .box form .message a {
            color: #45f3ff;
            text-decoration: none;
        }

        .box form .message a:hover {
            text-decoration: underline;
        }

        @keyframes animate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <style>
    .mensaje {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #333;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        z-index: 1000;
        animation: desaparecer 4s ease forwards;
    }

    @keyframes desaparecer {
        0% { opacity: 1; }
        80% { opacity: 1; }
        100% { opacity: 0; display: none; }
    }
</style>

</head>
<a href="index.html" class="volver-boton">Volver</a>

<style>
.volver-boton {
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 10px 20px;
    background-color: #ffffff10;
    border: 1px solid #ffffff50;
    border-radius: 12px;
    color: white;
    text-decoration: none;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    z-index: 999;
}

.volver-boton:hover {
    background-color: #ffffff20;
    border-color: #ffffff80;
}
.password-container {
  position: relative;
  display: flex;
  align-items: center;
}

.password-container input {
  width: 100%;
  padding-right: 40px; /* espacio para el icono */
}

.toggle-password {
  position: absolute;
  right: 10px;
  width: 24px;
  height: 24px;
  cursor: pointer;
}

</style>
</head>
<body>
<?php if (isset($mensaje)) echo "<div class='mensaje'>$mensaje</div>"; ?>
    <div class="box">
        <span class="borderLine"></span>
        <form method="post" action="login.php">
            <h2>Iniciar Sesión</h2>
            <div class="inputBox">
                <input type="text" name="usuario" required>
                <span>Usuario</span>
                <i></i>
            </div>
            <div class="inputBox">
                <div class="password-container">
  <input type="password" name="password" id="password" required>
  <img src="img/ojo cerrado.jpg" alt="Mostrar contraseña" class="toggle-password" onclick="togglepasswordVisibility(this)">
</div>
                <span>Contraseña</span>
                <i></i>
            </div>
            <input type="submit" value="Entrar">
            <div class="message">
                <p><?php echo $mensaje; ?></p>
            </div>
            <div class="message">
                <p>¿Aún no tienes cuenta? <a href="register.php">Regístrate</a></p>
            </div>
        </form>
    </div>
    <script>
  function togglepasswordVisibility(imgElement) {
    const input = imgElement.previousElementSibling;
    const openIcon = "img/ojo abierto.jpg";
    const closedIcon = "img/ojo cerrado.jpg";

    if (input.type === "password") {
      input.type = "text";
      imgElement.src = openIcon;
    } else {
      input.type = "password";
      imgElement.src = closedIcon;
    }
  }
</script>
</body>
</html>
