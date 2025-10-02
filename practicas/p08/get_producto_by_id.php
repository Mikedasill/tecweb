<?php
/* practicas/p8/get_producto_by_id.php */
header('Content-Type: text/plain; charset=utf-8');
require __DIR__.'/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { http_response_code(400); exit("Falta parámetro id\n"); }

$cn = db();
$stmt = $cn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

echo $row ? print_r($row, true) : "Sin resultados para id=$id\n";
