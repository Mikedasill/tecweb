<?php
    // Limpiar cualquier salida previa
    ob_start();
    
    include_once __DIR__.'/database.php';

    // Limpiar el buffer y establecer headers
    ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');

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

    // Establecer charset UTF-8
    $conexion->set_charset("utf8");

    // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
    $sql = "SELECT id, nombre, marca, modelo, precio, detalles, unidades, imagen 
            FROM productos 
            WHERE eliminado = 0 
            ORDER BY id DESC";
    
    $result = $conexion->query($sql);

    if(!$result) {
        // Error en la consulta
        $conexion->close();
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Error al obtener los productos: ' . mysqli_error($conexion)
        ), JSON_PRETTY_PRINT);
        exit;
    }

    // SE OBTIENEN LOS RESULTADOS
    if($result->num_rows > 0) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        // SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
        foreach($rows as $num => $row) {
            foreach($row as $key => $value) {
                // utf8_encode solo si es necesario (algunos sistemas ya usan UTF-8)
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