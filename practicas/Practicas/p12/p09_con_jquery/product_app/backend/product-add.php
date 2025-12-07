<?php
    // Esto ya debe tener la línea de mysqli_report()
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');
    
    // Asumimos error por defecto
    $data = array(
        'status'  => 'error',
        'message' => 'No se recibieron datos para agregar'
    );

    if(!empty($producto)) {
        // SE TRANSFORMA EL STRING DEL JASON A OBJETO
        $jsonOBJ = json_decode($producto);
        
        // --- AQUÍ INICIA EL TRY...CATCH ---
        // Ahora protege TODO el bloque
        try {
            $conexion->set_charset("utf8");

            // 1. PRIMERO HACEMOS LA VALIDACIÓN DE DUPLICADOS (AHORA PROTEGIDA)
            $sql_check = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
            $result = $conexion->query($sql_check);
            
            if ($result->num_rows > 0) {
                // Este es un error de lógica, no de BD
                $data['message'] = 'Ya existe un producto con ese nombre';
                $result->free();
            } else {
                // 2. SI NO HAY DUPLICADOS, INTENTAMOS INSERTAR (TAMBIÉN PROTEGIDA)
                $result->free();
                
                // Esta es la consulta que debe fallar con tus datos "rw1"
                $sql_insert = "INSERT INTO productos VALUES (null, '{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', '{$jsonOBJ->precio}', '{$jsonOBJ->detalles}', '{$jsonOBJ->unidades}', '{$jsonOBJ->imagen}', 0)";
                
                // Si la consulta es exitosa (ej. MySQL no estricto)
                if($conexion->query($sql_insert)){
                    $data['status'] =  "success";
                    $data['message'] =  "Producto agregado";
                } else {
                    // Esto no debería pasar con mysqli_report, pero es un seguro
                    $data['message'] = "ERROR: No se ejecuto $sql_insert. " . mysqli_error($conexion);
                }
            }
        } catch (mysqli_sql_exception $e) {
            // --- ¡AQUÍ SE ATRAPARÁ EL ERROR! ---
            // Si el SELECT falla o el INSERT falla (con "rw1"),
            // el código saltará aquí.
            $data['message'] = "ERROR de base de datos: " . $e->getMessage();
        }
        
        // Cierra la conexion
        $conexion->close();
    }

    // 4. AL FINAL, PASE LO QUE PASE, SE ENVÍA UN JSON VÁLIDO
    echo json_encode($data, JSON_PRETTY_PRINT);
?>