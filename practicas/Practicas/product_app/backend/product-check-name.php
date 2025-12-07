<?php
    include_once __DIR__.'/database.php';

    // Este script verifica si un nombre de producto ya existe
    
    // Arreglo de respuesta por defecto
    $data = array(
        'exists' => false
    );

    if (isset($_POST['nombre'])) {
        $nombre = $conexion->real_escape_string($_POST['nombre']);
        
        // Buscar un producto con ese nombre que NO esté eliminado
        $sql = "SELECT id FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0";
        
        if ($result = $conexion->query($sql)) {
            // Si el número de filas es mayor a 0, significa que ya existe
            if ($result->num_rows > 0) {
                $data['exists'] = true;
            }
            $result->free();
        }
        $conexion->close();
    }

    // Devolver siempre una respuesta JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>