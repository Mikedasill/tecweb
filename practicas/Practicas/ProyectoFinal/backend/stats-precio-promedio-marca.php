<?php
include_once __DIR__.'/database.php';

$data = [
    'labels' => [],
    'data'   => []
];

$sql = "SELECT marca, AVG(precio) AS promedio_precio 
        FROM productos 
        WHERE eliminado = 0
        GROUP BY marca";

if ($result = $conexion->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['marca'];
        $data['data'][]   = (float)$row['promedio_precio'];
    }
    $result->free();
}

$conexion->close();
echo json_encode($data, JSON_PRETTY_PRINT);
