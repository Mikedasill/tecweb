<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array();

    // Verificar que el método sea GET
    if($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Método no permitido'
        ), JSON_PRETTY_PRINT);
        exit;
    }

    // SE VERIFICA HABER RECIBIDO EL PARÁMETRO DE BÚSQUEDA
    if(!isset($_GET['search'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Parámetro de búsqueda no proporcionado'
        ), JSON_PRETTY_PRINT);
        exit;
    }

    // Obtener y limpiar el término de búsqueda
    $search = trim($_GET['search']);

    // Si la búsqueda está vacía, devolver array vacío
    if(empty($search)) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // Validar longitud del término de búsqueda (evitar búsquedas muy largas)
    if(strlen($search) > 100) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Término de búsqueda demasiado largo'
        ), JSON_PRETTY_PRINT);
        exit;
    }

    // Escapar el término de búsqueda para prevenir SQL Injection
    $searchEscaped = mysqli_real_escape_string($conexion, $search);

    // Establecer charset UTF-8
    $conexion->set_charset("utf8");

    // Preparar la consulta SQL
    // Busca en: id, nombre, marca y detalles
    $sql = "SELECT id, nombre, marca, modelo, precio, detalles, unidades, imagen 
            FROM productos 
            WHERE (
                id = '{$searchEscaped}' OR 
                nombre LIKE '%{$searchEscaped}%' OR 
                marca LIKE '%{$searchEscaped}%' OR 
                detalles LIKE '%{$searchEscaped}%'
            ) 
            AND eliminado = 0 
            ORDER BY 
                CASE 
                    WHEN nombre = '{$searchEscaped}' THEN 1
                    WHEN nombre LIKE '{$searchEscaped}%' THEN 2
                    WHEN marca = '{$searchEscaped}' THEN 3
                    ELSE 4
                END,
                nombre ASC
            LIMIT 50";

    // Ejecutar la consulta
    $result = $conexion->query($sql);

    if(!$result) {
        // Error en la consulta
        $conexion->close();
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Error en la búsqueda: ' . mysqli_error($conexion)
        ), JSON_PRETTY_PRINT);
        exit;
    }

    // SE OBTIENEN LOS RESULTADOS
    if($result->num_rows > 0) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        // SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
        foreach($rows as $num => $row) {
            foreach($row as $key => $value) {
                // Verificar si ya está en UTF-8
                if(mb_detect_encoding($value, 'UTF-8', true) === false) {
                    $data[$num][$key] = utf8_encode($value);
                } else {
                    $data[$num][$key] = $value;
                }
            }
            
            // Asegurar que los valores numéricos sean del tipo correcto
            $data[$num]['id'] = intval($data[$num]['id']);
            $data[$num]['precio'] = floatval($data[$num]['precio']);
            $data[$num]['unidades'] = intval($data[$num]['unidades']);
        }
    }

    // Liberar resultado
    $result->free();
    
    // Cerrar conexión
    $conexion->close();
    
    // Configurar cabeceras HTTP
    header('Content-Type: application/json; charset=utf-8');
    
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>