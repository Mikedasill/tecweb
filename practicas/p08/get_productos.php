<?php
/* practicas/p8/get_productos.php */
header('Content-Type: application/json; charset=utf-8');
require __DIR__.'/db.php';

$tope = isset($_GET['tope']) ? (float) $_GET['tope'] : 1e9;

$cn = db();
$stmt = $cn->prepare("SELECT * FROM productos WHERE precio <= ? ORDER BY precio ASC");
$stmt->bind_param("d", $tope);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($r = $res->fetch_assoc()) { $items[] = $r; }

echo json_encode([
  'count'   => count($items),
  'tope'    => $tope,
  'results' => $items
], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
