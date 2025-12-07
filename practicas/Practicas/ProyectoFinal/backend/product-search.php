<?php
    include_once __DIR__.'/database.php';

    $data = array();

    if ( isset($_GET['search']) ) {
        $search = $_GET['search'];

        $sql = "SELECT * FROM productos 
                WHERE (id = '{$search}' 
                    OR nombre LIKE '%{$search}%' 
                    OR marca LIKE '%{$search}%' 
                    OR detalles LIKE '%{$search}%')
                  AND eliminado = 0";

        if ( $result = $conexion->query($sql) ) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            if (!is_null($rows)) {
                foreach($rows as $num => $row) {
                    foreach($row as $key => $value) {
                        $data[$num][$key] = $value; // sin utf8_encode
                    }
                }
            }
            $result->free();
        } else {
            die('Query Error: '.mysqli_error($conexion));
        }
        $conexion->close();
    }

    echo json_encode($data, JSON_PRETTY_PRINT);
?>
