<?php
    include_once __DIR__.'/database.php';

    $data = array();

    if ( isset($_GET['id']) ) {
        $id = $_GET['id'];

        $sql = "SELECT * FROM productos WHERE id = {$id}";
        if ( $result = $conexion->query($sql) ) {
            $row = $result->fetch_assoc();

            if (!is_null($row)) {
                foreach($row as $key => $value) {
                    $data[$key] = $value; // sin utf8_encode
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

