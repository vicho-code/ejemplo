<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegurar que todos los campos existan
    $username = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($username === '' || $email === '' || $password === '') {
        $mensaje = '⚠️ Completa todos los campos.';
    } else {
        // Guarda directamente el texto (sin hash)
        $stmt = $conn->prepare("INSERT INTO usuarios (username, email, password, role, created_at) VALUES (?, ?, ?, 'user', NOW())");
        $stmt->bind_param('sss', $username, $email, $password);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            header('Location: panel.usuario.php');
            exit;
        } else {
            $mensaje = '❌ Error al registrar usuario: ' . $conn->error;
        }

        $stmt->close();
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Registro</title>
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
    color: #23242a;
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
<body>
<?php if (isset($mensaje)) echo "<div class='mensaje'>$mensaje</div>"; ?>
    <div class="box">
        <span class="borderLine"></span>
        <form method="POST" enctype="multipart/form-data">
            <h2>Create Account</h2>
            <div class="inputBox">
                <input type="text" name="usuario" required>
                <span>Usuario</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="email" name="email" required>
                <span>Email</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="password" name="password" required>
                <span>Password</span>
                <i></i>
            </div>
            <label for="foto">Foto de perfil</label>
            <input type="file" name="foto" accept="image/*">
            <input type="submit" name="register" value="Register">
            <div class="message">
                <p><?php echo $mensaje; ?></p>
            </div>
            <div class="message">
                <p> ¿Ya tienes cuenta? <a href="login.php" style="color: #45f3ff;">Inicia sesión</a></p>
            </div>
        </form>
    </div>
</body>
</html>