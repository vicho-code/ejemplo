<?php
// --- admin.php ---
// Panel administrador protegido

if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'conexion.php';

// Solo admins pueden entrar
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Buscar usuario actual
$stmt = $conn->prepare("SELECT id, username, role FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || $user['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// --- Actualizar precios de productos ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['price'])) {
    $pid = intval($_POST['product_id']);
    $price = floatval($_POST['price']);
    $stmt = $conn->prepare("UPDATE productos SET precio = ? WHERE id = ?");
    $stmt->bind_param('di', $price, $pid);
    $stmt->execute();
    $stmt->close();
    $msg = "✅ Precio actualizado.";
}

// --- Obtener datos de la DB ---
$usuarios = $conn->query("SELECT id, username, email, role FROM usuarios")->fetch_all(MYSQLI_ASSOC);
$productos = $conn->query("SELECT id, nombre, precio FROM productos")->fetch_all(MYSQLI_ASSOC);
$pedidos   = $conn->query("SELECT id, cliente, fecha, estado FROM pedidos")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administrador</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}
    body{background:#23242a;color:#e0e0e0;min-height:100vh;display:flex;flex-direction:column}
    header{background:linear-gradient(135deg,#45f3ff,#1c1c1c);padding:25px 20px;text-align:center;color:#45f3ff;
      font-weight:600;letter-spacing:.1em;font-size:1.8em;box-shadow:0 4px 10px rgba(69,243,255,.3)}
    nav{background:#1c1c1c;padding:12px 0;display:flex;justify-content:center;gap:25px;box-shadow:inset 0 -2px 5px #45f3ff44}
    nav a{color:#45f3ff;text-decoration:none;font-weight:500;font-size:1em;cursor:pointer;transition:.3s}
    nav a:hover{color:#72dfff;text-decoration:underline}
    section{padding:30px 20px;flex-grow:1}
    h2{color:#45f3ff;margin-bottom:20px;font-weight:600;letter-spacing:.1em;font-size:1.4em}
    .tabla-container{background:#1c1c1c;border:2px solid #45f3ff;border-radius:12px;padding:20px;box-shadow:0 0 15px #45f3ff66;overflow-x:auto}
    table{width:100%;border-collapse:collapse;color:#e0e0e0}
    th,td{padding:12px 16px;text-align:left;border-bottom:1px solid #45f3ff22}
    th{background:#111;color:#45f3ff;font-weight:600;font-size:.95em;letter-spacing:.05em}
    tr:hover{background:#2c2f38}
    .btn{padding:8px 14px;border:none;border-radius:8px;cursor:pointer;font-size:14px;font-weight:500;margin-right:5px;
      background:#000;color:#e0e0e0;transition:.3s}
    .btn-editar:hover{color:#45f3ff;box-shadow:0 0 10px #45f3ff,0 0 20px #45f3ff}
    .btn-eliminar:hover{color:#ff4545;box-shadow:0 0 10px #ff4545,0 0 20px #ff4545}
    .dashboard-cards{display:flex;gap:20px;flex-wrap:wrap}
    .card{flex:1;min-width:200px;background:#1c1c1c;border:2px solid #45f3ff;border-radius:12px;padding:20px;text-align:center;
      box-shadow:0 0 15px #45f3ff66;transition:.3s}
    .card:hover{transform:translateY(-5px);box-shadow:0 0 25px #72dfffcc}
    .card h3{color:#45f3ff;margin-bottom:10px}
    footer{background:#1c1c1c;color:#45f3ff;text-align:center;padding:15px 0;font-weight:500;letter-spacing:.05em;
      box-shadow:0 -3px 6px #45f3ff44;position:fixed;width:100%;bottom:0}
    .content-section{display:none}
    .content-section.active{display:block}
  </style>
</head>
<body>
  <header>Panel de Administrador</header>
  <nav>
    <a onclick="mostrarSeccion('dashboard')">Dashboard</a>
    <a onclick="mostrarSeccion('usuarios')">Usuarios</a>
    <a onclick="mostrarSeccion('productos')">Productos</a>
    <a onclick="mostrarSeccion('pedidos')">Pedidos</a>
    <a href="logout.php">Salir</a>
  </nav>

  <section>
    <!-- Dashboard -->
    <div id="dashboard" class="content-section active">
      <h2>Resumen General</h2>
      <div class="dashboard-cards">
        <div class="card"><h3>Usuarios</h3><p><?php echo count($usuarios); ?> registrados</p></div>
        <div class="card"><h3>Productos</h3><p><?php echo count($productos); ?> activos</p></div>
        <div class="card"><h3>Pedidos</h3><p><?php echo count($pedidos); ?> en total</p></div>
      </div>
    </div>

    <!-- Usuarios -->
    <div id="usuarios" class="content-section">
      <h2>Gestión de Usuarios</h2>
      <div class="tabla-container">
        <table>
          <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th></tr></thead>
          <tbody>
            <?php foreach ($usuarios as $u): ?>
            <tr>
              <td><?php echo $u['id']; ?></td>
              <td><?php echo htmlspecialchars($u['username']); ?></td>
              <td><?php echo htmlspecialchars($u['email']); ?></td>
              <td><?php echo htmlspecialchars($u['role']); ?></td>
              <td>
                <button class="btn btn-editar">Editar</button>
                <button class="btn btn-eliminar">Eliminar</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Productos -->
    <div id="productos" class="content-section">
      <h2>Gestión de Productos</h2>
      <div class="tabla-container">
        <?php if (isset($msg)) echo "<p style='color:lightgreen'>$msg</p>"; ?>
        <table>
          <thead><tr><th>ID</th><th>Producto</th><th>Precio</th><th>Acciones</th></tr></thead>
          <tbody>
            <?php foreach ($productos as $p): ?>
            <tr>
              <td><?php echo $p['id']; ?></td>
              <td><?php echo htmlspecialchars($p['nombre']); ?></td>
              <td>
                <form method="post" style="margin:0;display:flex;gap:5px;align-items:center">
                  <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                  <input name="price" value="<?php echo $p['precio']; ?>" style="width:80px">
                  <button type="submit" class="btn btn-editar">Actualizar</button>
                </form>
              </td>
              <td><button class="btn btn-eliminar">Eliminar</button></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pedidos -->
    <div id="pedidos" class="content-section">
      <h2>Gestión de Pedidos</h2>
      <div class="tabla-container">
        <table>
          <thead><tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead>
          <tbody>
            <?php foreach ($pedidos as $ped): ?>
            <tr>
              <td><?php echo $ped['id']; ?></td>
              <td><?php echo htmlspecialchars($ped['cliente']); ?></td>
              <td><?php echo $ped['fecha']; ?></td>
              <td><?php echo htmlspecialchars($ped['estado']); ?></td>
              <td>
                <button class="btn btn-editar">Actualizar</button>
                <button class="btn btn-eliminar">Eliminar</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <footer>© 2025 La Casa del Pan - Panel de Administrador</footer>

  <script>
    function mostrarSeccion(id) {
      document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
      document.getElementById(id).classList.add('active');
    }
  </script>
</body>
</html>
