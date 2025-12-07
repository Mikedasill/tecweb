<?php
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $producto = file_get_contents('php://input');
    
    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array(
        'status'  => 'error', // Asumimos error por defecto
        'message' => 'No se recibieron datos para actualizar'
    );
    
    if(!empty($producto)) {
        // SE TRANSFORMA EL STRING DEL JASON A OBJETO
        $jsonOBJ = json_decode($producto);
        
        try {
            $conexion->set_charset("utf8");
            
            // --- ¡AQUÍ ESTÁ LA CORRECCIÓN! ---
            // Se agregaron comillas simples ' ' alrededor de 
            // precio, unidades, e id.
            $sql = "UPDATE productos SET 
                nombre = '{$jsonOBJ->nombre}', 
                marca = '{$jsonOBJ->marca}', 
                modelo = '{$jsonOBJ->modelo}', 
                precio = '{$jsonOBJ->precio}', 
                detalles = '{$jsonOBJ->detalles}', 
                unidades = '{$jsonOBJ->unidades}', 
                imagen = '{$jsonOBJ->imagen}' 
            WHERE id = '{$jsonOBJ->id}'";
            // --- FIN DE LA CORRECCIÓN ---
            
            $conexion->query($sql);

            // Si la línea anterior no falló, llegamos aquí y fue un éxito
            $data['status'] =  "success";
            $data['message'] =  "Producto actualizado";

        } catch (mysqli_sql_exception $e) {
            // Ahora esto SÍ atrapará el error "Data truncated"
            $data['message'] = "ERROR de base de datos: " . $e->getMessage();
        }

        // Cierra la conexion
        $conexion->close();
    }

    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>