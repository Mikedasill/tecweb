<?php
require_once __DIR__ . "/conexion.php";
$sql = "SELECT id, nombre, modelo, marca, precio, existencias, imagen_url
        FROM productos
        WHERE eliminado = 0
        ORDER BY id DESC";
$rs = $mysqli->query($sql);

echo "<!DOCTYPE html><html lang='es'><head><meta charset='utf-8'><title>Productos vigentes</title>
<style>
table{border-collapse:collapse;width:100%;font-family:system-ui}
th,td{border:1px solid #ccc;padding:.5rem;text-align:left}
img{max-width:90px}
</style></head><body>";

echo "<h1>Productos vigentes</h1>";

if ($rs && $rs->num_rows) {
  echo "<table><thead><tr>
        <th>ID</th><th>Nombre</th><th>Modelo</th><th>Marca</th>
        <th>Precio</th><th>Existencias</th><th>Imagen</th>
        </tr></thead><tbody>";
  while ($row = $rs->fetch_assoc()) {
    $img = $row['imagen_url'] ?: 'img/default.png';
    echo "<tr>";
    echo "<td>".htmlspecialchars($row['id'])."</td>";
    echo "<td>".htmlspecialchars($row['nombre'])."</td>";
    echo "<td>".htmlspecialchars($row['modelo'])."</td>";
    echo "<td>".htmlspecialchars($row['marca'])."</td>";
    echo "<td>$".htmlspecialchars($row['precio'])."</td>";
    echo "<td>".htmlspecialchars($row['existencias'])."</td>";
    echo "<td><img src='".htmlspecialchars($img)."' alt='img'></td>";
    echo "</tr>";
  }
  echo "</tbody></table>";
} else {
  echo "<p>No hay productos vigentes.</p>";
}

echo "<p><a href='formulario_productos.html'>Nuevo producto</a></p>";
echo "</body></html>";
