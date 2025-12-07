<?php
    include_once __DIR__.'/database.php';

    // Respuesta por defecto
    $data = array(
        'labels' => [],
        'recursos_por_marca' => [],
        'unidades_por_marca' => [],
        'precio_promedio_por_marca' => []
    );

    // Consulta: agrupamos por marca
    $sql = "
        SELECT 
            marca,
            COUNT(*)        AS total_recursos,
            SUM(unidades)   AS total_unidades,
            AVG(precio)     AS precio_promedio
        FROM productos
        WHERE eliminado = 0
        GROUP BY marca
    ";

    if ($result = $conexion->query($sql)) {
        while($row = $result->fetch_assoc()) {
            // Etiquetas (marcas)
            $data['labels'][] = utf8_encode($row['marca']);

            // Número de recursos por marca
            $data['recursos_por_marca'][] = (int)$row['total_recursos'];

            // Unidades totales por marca
            $data['unidades_por_marca'][] = (int)$row['total_unidades'];

            // Precio promedio por marca
            $data['precio_promedio_por_marca'][] = (float)$row['precio_promedio'];
        }
        $result->free();
    } else {
        // En caso de error de consulta puedes enviar un mensaje si quieres
        // $data['error'] = "Error en la consulta: " . mysqli_error($conexion);
    }

    $conexion->close();

    // Devolver JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>
