<?php
declare(strict_types=1);
ini_set('display_errors', '1'); error_reporting(E_ALL);

require_once __DIR__ . '/conexion.php';
if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
  die('No hay conexión ($mysqli) — revisa conexion.php');
}

$sql = "SELECT id,nombre,modelo,marca,precio,existencias
        FROM productos
        WHERE eliminado = 0
        ORDER BY id DESC";
$rs = $mysqli->query($sql);
if ($rs === false) {
  die('Error en query: ' . $mysqli->error);
}
?>
<!doctype html>
<meta charset="utf-8">
<title>Productos vigentes v2</title>
<style>
  body{font-family:system-ui,Arial;margin:24px}
  table{border-collapse:collapse;width:100%}
  th,td{border:1px solid #ddd;padding:8px}
  th{background:#f4f4f4}
  a.btn{padding:.35rem .6rem;border:1px solid #111;border-radius:6px;text-decoration:none;color:#111}
</style>

<h2>Productos vigentes (eliminado=0)</h2>
<table>
  <tr>
    <th>ID</th><th>Nombre</th><th>Modelo</th><th>Marca</th>
    <th>Precio</th><th>Exist.</th><th>Acciones</th>
  </tr>
  <?php if ($rs->num_rows === 0): ?>
    <tr><td colspan="7" style="text-align:center;color:#666">Sin registros vigentes</td></tr>
  <?php else: while($r = $rs->fetch_assoc()): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= htmlspecialchars($r['nombre']) ?></td>
      <td><?= htmlspecialchars($r['modelo']) ?></td>
      <td><?= htmlspecialchars($r['marca']) ?></td>
      <td><?= number_format((float)$r['precio'], 2) ?></td>
      <td><?= (int)$r['existencias'] ?></td>
      <td><a class="btn" href="formulario_productos_v2.php?id=<?= $r['id'] ?>">Editar</a></td>
    </tr>
  <?php endwhile; endif; $rs->close(); ?>
</table>

<p style="margin-top:14px">
  <a class="btn" href="get_productos_xhtml_v2.php">Ver todos</a>
  <a class="btn" href="formulario_productos_v2.php" style="margin-left:8px">Nuevo</a>
</p>

