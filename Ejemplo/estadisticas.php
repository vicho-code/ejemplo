<?php
// estadisticas.php - solo accesible por admin
require_once 'functions.php';
require_admin();

// total usuarios
$res = $conn->query('SELECT COUNT(*) as total FROM usuarios');
$total = $res->fetch_assoc()['total'];

// usuarios conectados en los últimos 5 minutos
$stmt = $conn->prepare("SELECT COUNT(*) as conectados FROM usuarios WHERE last_active > (NOW() - INTERVAL 5 MINUTE)");
$stmt->execute();
$r = $stmt->get_result();
$con = $r->fetch_assoc()['conectados'];
$stmt->close();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Estadísticas</title></head><body>
<?php include 'header.php'; ?>
<h2>Estadísticas</h2>
<p>Total usuarios: <strong><?php echo intval($total); ?></strong></p>
<p>Usuarios conectados (últimos 5 minutos): <strong><?php echo intval($con); ?></strong></p>
</body></html>