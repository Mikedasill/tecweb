<?php
declare(strict_types=1);
ini_set('display_errors', '1'); error_reporting(E_ALL);

require_once __DIR__ . '/conexion.php';
if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
  die('No hay conexión ($mysqli) — revisa conexion.php');
}

$sql = "SELECT id,nombre,modelo,marca,precio,existencias,eliminado
        FROM productos
        ORDER BY id DESC";
$rs = $mysqli->query($sql);
if ($rs === false) {
  die('Error en query: ' . $mysqli->error);
}
?>
<!doctype html>
<meta charset="utf-8">
<title>Productos v2 (todos)</title>
<style>
  body{font-family:system-ui,Arial;margin:24px}
  table{border-collapse:collapse;width:100%}
  th,td{border:1px solid #ddd;padding:8px}
  th{background:#f4f4f4}
  .pill{padding:.15rem .5rem;border-radius:999px;font-size:.8rem}
  .ok{background:#e6ffe6;border:1px solid #8cc68c}
  .off{background:#ffecec;border:1px solid #e09b9b}
  a.btn{padding:.35rem .6rem;border:1px solid #111;border-radius:6px;text-decoration:none;color:#111}
</style>

<h2>Productos (todos)</h2>
<table>
  <tr>
    <th>ID</th><th>Nombre</th><th>Modelo</th><th>Marca</th>
    <th>Precio</th><th>Exist.</th><th>Estatus</th><th>Acciones</th>
  </tr>
  <?php if ($rs->num_rows === 0): ?>
    <tr><td colspan="8" style="text-align:center;color:#666">Sin registros</td></tr>
  <?php else: while($r = $rs->fetch_assoc()): ?>
    <tr>
      <td><?= $r['id'] ?></td>
      <td><?= htmlspecialchars($r['nombre']) ?></td>
      <td><?= htmlspecialchars($r['modelo']) ?></td>
      <td><?= htmlspecialchars($r['marca']) ?></td>
      <td><?= number_format((float)$r['precio'], 2) ?></td>
      <td><?= (int)$r['existencias'] ?></td>
      <td>
        <?php if ((int)$r['eliminado'] === 0): ?>
          <span class="pill ok">vigente</span>
        <?php else: ?>
          <span class="pill off">eliminado</span>
        <?php endif; ?>
      </td>
      <td><a class="btn" href="formulario_productos_v2.php?id=<?= $r['id'] ?>">Editar</a></td>
    </tr>
  <?php endwhile; endif; $rs->close(); ?>
</table>

<p style="margin-top:14px">
  <a class="btn" href="get_productos_vigentes_v2.php">Ver solo vigentes</a>
  <a class="btn" href="formulario_productos_v2.php" style="margin-left:8px">Nuevo</a>
</p>