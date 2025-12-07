<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array();
    // SE VERIFICA HABER RECIBIDO EL ID
    if( isset($_GET['id']) ) {
        $id = $_GET['id'];
        
        // SE REALIZA LA QUERY DE BÚSQUEDA
        $sql = "SELECT * FROM productos WHERE id = {$id}";
        if ( $result = $conexion->query($sql) ) {
            
            // SE OBTIENE EL RESULTADO (DEBE SER ÚNICO)
			$row = $result->fetch_assoc();

            if(!is_null($row)) {
                // SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
                foreach($row as $key => $value) {
                    $data[$key] = utf8_encode($value);
                }
            }
			$result->free();
		} else {
            die('Query Error: '.mysqli_error($conexion));
        }
		$conexion->close();
    } 
    
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON (OBJETO ÚNICO)
    echo json_encode($data, JSON_PRETTY_PRINT);
?>