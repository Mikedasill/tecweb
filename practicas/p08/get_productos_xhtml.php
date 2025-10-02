<?php
/* practicas/p8/get_productos_xhtml.php */
header('Content-Type: application/xhtml+xml; charset=utf-8');
require __DIR__.'/db.php';

$tope = isset($_GET['tope']) ? (int) $_GET['tope'] : 0;

$cn = db();
$stmt = $cn->prepare("SELECT * FROM productos WHERE unidades <= ? ORDER BY unidades ASC, nombre ASC");
$stmt->bind_param("i", $tope);
$stmt->execute();
$res = $stmt->get_result();

function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
  <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
  <title>PRODUCTO</title>
  <style type="text/css">
    body{font-family:Segoe UI,Arial,sans-serif;margin:20px}
    table{border-collapse:collapse;width:100%}
    th,td{border:1px solid #ccc;padding:6px 8px;font-size:14px}
    th{background:#f3f6f9;text-align:left}
    img{max-height:70px}
    caption{font-weight:600;font-size:18px;margin:0 0 10px 0}
  </style>
</head>
<body>
  <h1>PRODUCTO</h1>

  <table>
    <caption>Unidades ≤ <?php echo (int)$tope; ?></caption>
    <tr>
      <th>#</th><th>Nombre</th><th>Marca</th><th>Modelo</th>
      <th>Precio</th><th>Unidades</th><th>Detalles</th><th>Imagen</th>
    </tr>
<?php
$i = 1;
while ($row = $res->fetch_assoc()):
  $img = !empty($row['imagen']) ? 'img/' . $row['imagen'] : '';
?>
    <tr>
      <td><?php echo $i++; ?></td>
      <td><?php echo h($row['nombre']); ?></td>
      <td><?php echo h($row['marca']); ?></td>
      <td><?php echo h($row['modelo']); ?></td>
      <td><?php echo number_format((float)$row['precio'], 2); ?></td>
      <td><?php echo (int)$row['unidades']; ?></td>
      <td><?php echo h($row['detalles']); ?></td>
      <td><?php if ($img): ?><img src="<?php echo h($img); ?>" alt="producto" /><?php endif; ?></td>
    </tr>
<?php endwhile; ?>
  </table>
</body>
</html>
