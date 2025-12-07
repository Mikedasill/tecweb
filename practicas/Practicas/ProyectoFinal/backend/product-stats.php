<?php
include_once __DIR__ . '/database.php';

$data = [
    'labels' => [],
    'total_recursos' => [],
    'total_unidades' => [],
    'promedio_precio' => []
];

$sql = "
    SELECT 
        marca,
        COUNT(*) AS total_recursos,
        SUM(unidades) AS total_unidades,
        AVG(precio) AS promedio_precio
    FROM productos
    WHERE eliminado = 0
    GROUP BY marca
";

if ($result = $conexion->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['marca'];
        $data['total_recursos'][] = (int)$row['total_recursos'];
        $data['total_unidades'][] = (int)$row['total_unidades'];
        $data['promedio_precio'][] = (float)$row['promedio_precio'];
    }
    $result->free();
}

$conexion->close();

echo json_encode($data, JSON_PRETTY_PRINT);
