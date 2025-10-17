<?php
// product_app/backend/create.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexion.php';
$mysqli = db();

// Aceptamos JSON o x-www-form-urlencoded
$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);
if (!is_array($payload)) {
    // intentar desde POST normal
    $payload = $_POST;
}

$nombre      = trim($payload['nombre']  ?? '');
$modelo      = trim($payload['modelo']  ?? '');
$marca       = trim($payload['marca']   ?? '');
$precio      = (float)($payload['precio'] ?? 0);
$existencias = (int)  ($payload['existencias'] ?? 0);
$descripcion = trim($payload['descripcion'] ?? '');
$imagen_url  = trim($payload['imagen_url']  ?? 'default.png');

// Validaciones básicas
$errors = [];
if ($nombre === '')        $errors[] = 'nombre requerido';
if ($modelo === '')        $errors[] = 'modelo requerido';
if ($marca === '')         $errors[] = 'marca requerida';
if ($precio <= 0)          $errors[] = 'precio > 0';
if ($existencias < 0)      $errors[] = 'existencias >= 0';

if ($errors) {
    echo json_encode(['ok' => false, 'error' => implode(', ', $errors)], JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "INSERT INTO productos
           (nombre, modelo, marca, precio, existencias, descripcion, imagen_url, eliminado)
        VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    echo json_encode(['ok' => false, 'error' => 'Prepare: ' . $mysqli->error], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param(
    'sssdisd',
    $nombre,
    $modelo,
    $marca,
    $precio,
    $existencias,
    $descripcion,
    $imagen_url
);

$ok = $stmt->execute();
if (!$ok) {
    echo json_encode(['ok' => false, 'error' => 'Execute: ' . $stmt->error], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['ok' => true, 'id' => $stmt->insert_id], JSON_UNESCAPED_UNICODE);
