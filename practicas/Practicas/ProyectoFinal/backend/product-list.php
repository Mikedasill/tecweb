<?php
    include_once __DIR__.'/database.php';

    $data = array();

    if ( $result = $conexion->query("SELECT * FROM productos WHERE eliminado = 0") ) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        if (!is_null($rows)) {
            foreach($rows as $num => $row) {
                foreach($row as $key => $value) {
                    // OJO: ya no usamos utf8_encode aquí
                    $data[$num][$key] = $value;
                }
            }
        }
        $result->free();
    } else {
        die('Query Error: '.mysqli_error($conexion));
    }
    $conexion->close();

    echo json_encode($data, JSON_PRETTY_PRINT);
?>

