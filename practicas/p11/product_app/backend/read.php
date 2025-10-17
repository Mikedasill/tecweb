<?php
// product_app/backend/read.php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexion.php';
$mysqli = db();

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '') {
    echo json_encode(['ok' => false, 'error' => 'Parámetro q vacío']);
    exit;
}

$sql = "SELECT id, nombre, modelo, marca, precio, existencias, descripcion, imagen_url
        FROM productos
        WHERE eliminado = 0
          AND (nombre LIKE ? OR modelo LIKE ? OR marca LIKE ? OR descripcion LIKE ?)
        ORDER BY id DESC
        LIMIT 50";

$like = "%{$q}%";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    echo json_encode(['ok' => false, 'error' => 'Prepare: ' . $mysqli->error]);
    exit;
}
$stmt->bind_param('ssss', $like, $like, $like, $like);
$stmt->execute();

$res   = $stmt->get_result();
$rows  = $res->fetch_all(MYSQLI_ASSOC);

echo json_encode(['ok' => true, 'data' => $rows], JSON_UNESCAPED_UNICODE);

