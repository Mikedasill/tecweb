<?php
// set_producto_v2.php
require_once __DIR__ . "/conexion.php";

// 1) Tomar datos del POST
$nombre      = trim($_POST['nombre']      ?? '');
$modelo      = trim($_POST['modelo']      ?? '');
$marca       = trim($_POST['marca']       ?? '');
$precio      = trim($_POST['precio']      ?? '');
$existencias = trim($_POST['existencias'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$imagen_url  = trim($_POST['imagen_url']  ?? '');

// 2) Validaciones mínimas del lado servidor
$errores = [];
if ($nombre === '' || $modelo === '' || $marca === '') {
  $errores[] = "Nombre, modelo y marca son obligatorios.";
}
if ($precio === '' || !is_numeric($precio) || $precio < 0) {
  $errores[] = "Precio inválido.";
}
if ($existencias === '' || !ctype_digit((string)$existencias) || (int)$existencias < 0) {
  $errores[] = "Existencias inválidas.";
}

// 3) Verificar duplicado (nombre+modelo+marca) en productos vigentes
if (!$errores) {
  $sqlDup = "SELECT COUNT(*) AS n
             FROM productos
             WHERE nombre = ? AND modelo = ? AND marca = ? AND eliminado = 0";
  $stDup = $mysqli->prepare($sqlDup);
  if (!$stDup) {
    $errores[] = "Error interno (prepare dup): " . $mysqli->error;
  } else {
    $stDup->bind_param("sss", $nombre, $modelo, $marca);
    $stDup->execute();
    $resDup = $stDup->get_result()->fetch_assoc();
    if (($resDup['n'] ?? 0) > 0) {
      $errores[] = "Ya existe un producto con ese nombre, modelo y marca.";
    }
    $stDup->close();
  }
}

// 4) Responder con errores si los hay
if ($errores) {
  echo "<!DOCTYPE html><html lang='es'><head><meta charset='utf-8'><title>Error</title></head><body>";
  echo "<h1>Alta de producto – Error</h1><ul>";
  foreach ($errores as $e) echo "<li>".htmlspecialchars($e, ENT_QUOTES, 'UTF-8')."</li>";
  echo "</ul><p><a href='formulario_productos.html'>Volver al formulario</a></p></body></html>";
  exit;
}

// 5) Imagen por defecto si no se proporcionó
if ($imagen_url === '') {
  $imagen_url = "img/default.png";
}

// 6) INSERT usando column names; eliminado=0 por defecto/forzado
$sql = "INSERT INTO productos
        (nombre, modelo, marca, precio, existencias, descripcion, imagen_url, eliminado)
        VALUES (?,?,?,?,?,?,?, 0)";
$st = $mysqli->prepare($sql);
if (!$st) {
  die("<!DOCTYPE html><meta charset='utf-8'><h1>Error</h1><p>Prepare falló: "
      . htmlspecialchars($mysqli->error) . "</p>");
}

// ⚠️ Cadena corregida: 7 parámetros → "sssdiss"
$st->bind_param(
  "sssdiss",
  $nombre,        // s
  $modelo,        // s
  $marca,         // s
  $precio,        // d (número con punto decimal)
  $existencias,   // i (entero)
  $descripcion,   // s
  $imagen_url     // s
);

$ok = $st->execute();
$err = $st->error;
$st->close();

// 7) Respuesta HTML
echo "<!DOCTYPE html><html lang='es'><head><meta charset='utf-8'><title>Resultado</title></head><body>";
if ($ok) {
  echo "<h1>Producto insertado correctamente</h1>";
  echo "<ul>";
  echo "<li><b>Nombre:</b> " . htmlspecialchars($nombre) . "</li>";
  echo "<li><b>Modelo:</b> " . htmlspecialchars($modelo) . "</li>";
  echo "<li><b>Marca:</b> " . htmlspecialchars($marca) . "</li>";
  echo "<li><b>Precio:</b> " . htmlspecialchars($precio) . "</li>";
  echo "<li><b>Existencias:</b> " . htmlspecialchars($existencias) . "</li>";
  echo "<li><b>Descripción:</b> " . nl2br(htmlspecialchars($descripcion)) . "</li>";
  echo "<li><b>Imagen:</b> <img src='" . htmlspecialchars($imagen_url) . "' alt='foto' style='max-width:160px'></li>";
  echo "</ul>";
} else {
  echo "<h1>Error al insertar</h1>";
  echo "<p>" . htmlspecialchars($err ?: $mysqli->error) . "</p>";
}
echo "<p><a href='formulario_productos.html'>Capturar otro</a> | <a href='get_productos_vigentes.php'>Ver vigentes</a></p>";
echo "</body></html>";
