<?php
    // Limpiar cualquier salida previa
    ob_start();
    
    include_once __DIR__.'/database.php';

    // Limpiar el buffer y establecer headers
    ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array(
        'status'  => 'error',
        'message' => 'Ocurrió un error desconocido'
    );

    // Verificar que se recibió la petición por POST
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $data['message'] = 'Método no permitido';
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // SE VERIFICA HABER RECIBIDO EL ID
    if(!isset($_POST['id']) || empty($_POST['id'])) {
        $data['status'] = 'error';
        $data['message'] = 'ID de producto no proporcionado';
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // Recibir los datos del producto
    $product = $_POST;

    // ===== VALIDACIONES DEL LADO DEL SERVIDOR =====
    $errors = [];

    // Validar ID (debe ser numérico y positivo)
    if(!is_numeric($product['id']) || intval($product['id']) <= 0) {
        $errors[] = 'ID de producto inválido.';
    }

    // Validar NOMBRE (requerido, máximo 100 caracteres)
    if(empty($product['nombre']) || trim($product['nombre']) === '') {
        $errors[] = 'El nombre del producto es obligatorio.';
    } elseif(strlen($product['nombre']) > 100) {
        $errors[] = 'El nombre no puede exceder 100 caracteres.';
    }

    // Validar MARCA (requerida)
    if(empty($product['marca']) || trim($product['marca']) === '') {
        $errors[] = 'La marca es obligatoria.';
    }

    // Validar MODELO (requerido, alfanumérico, máximo 25 caracteres)
    if(empty($product['modelo']) || trim($product['modelo']) === '') {
        $errors[] = 'El modelo es obligatorio.';
    } elseif(!preg_match('/^[a-zA-Z0-9\-]+$/', $product['modelo'])) {
        $errors[] = 'El modelo solo puede contener letras, números y guiones.';
    } elseif(strlen($product['modelo']) > 25) {
        $errors[] = 'El modelo no puede exceder 25 caracteres.';
    }

    // Validar PRECIO (requerido, numérico, entre 0.01 y 99.99)
    if(empty($product['precio']) && $product['precio'] !== '0') {
        $errors[] = 'El precio es obligatorio.';
    } elseif(!is_numeric($product['precio'])) {
        $errors[] = 'El precio debe ser un número válido.';
    } elseif(floatval($product['precio']) < 0.01 || floatval($product['precio']) > 99.99) {
        $errors[] = 'El precio debe estar entre 0.01 y 99.99.';
    }

    // Validar UNIDADES (requerido, entero, mayor o igual a 0)
    if(empty($product['unidades']) && $product['unidades'] !== '0') {
        $errors[] = 'Las unidades son obligatorias.';
    } elseif(!is_numeric($product['unidades']) || intval($product['unidades']) != $product['unidades']) {
        $errors[] = 'Las unidades deben ser un número entero.';
    } elseif(intval($product['unidades']) < 0) {
        $errors[] = 'Las unidades no pueden ser negativas.';
    }

    // Validar DETALLES (opcional, máximo 250 caracteres)
    if(isset($product['detalles']) && strlen($product['detalles']) > 250) {
        $errors[] = 'Los detalles no pueden exceder 250 caracteres.';
    }

    // Validar IMAGEN (opcional, pero si está vacía usar default)
    if(empty($product['imagen']) || trim($product['imagen']) === '') {
        $product['imagen'] = 'img/default.png';
    }

    // Si hay errores, devolver respuesta con todos los errores
    if(count($errors) > 0) {
        $data['status'] = 'error';
        $data['message'] = implode(' ', $errors);
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // Convertir ID a entero
    $id = intval($product['id']);

    // ===== VERIFICAR QUE EL PRODUCTO EXISTE =====
    $sqlCheck = "SELECT id, nombre FROM productos WHERE id = {$id} AND eliminado = 0";
    $resultCheck = $conexion->query($sqlCheck);

    if(!$resultCheck) {
        $data['status'] = 'error';
        $data['message'] = 'Error al verificar el producto: ' . mysqli_error($conexion);
        $conexion->close();
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    if($resultCheck->num_rows === 0) {
        $data['status'] = 'error';
        $data['message'] = 'El producto no existe o fue eliminado';
        $resultCheck->free();
        $conexion->close();
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
    $resultCheck->free();

    // ===== VERIFICAR QUE EL NOMBRE NO EXISTA EN OTRO PRODUCTO =====
    $nombreEscapado = mysqli_real_escape_string($conexion, trim($product['nombre']));
    $sqlCheckNombre = "SELECT id FROM productos WHERE nombre = '{$nombreEscapado}' AND id != {$id} AND eliminado = 0";
    $resultCheckNombre = $conexion->query($sqlCheckNombre);

    if($resultCheckNombre && $resultCheckNombre->num_rows > 0) {
        $data['status'] = 'error';
        $data['message'] = 'Ya existe otro producto con ese nombre';
        $resultCheckNombre->free();
        $conexion->close();
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
    if($resultCheckNombre) {
        $resultCheckNombre->free();
    }

    // ===== ACTUALIZAR PRODUCTO EN LA BASE DE DATOS =====
    // Escapar datos para prevenir SQL Injection
    $nombre = mysqli_real_escape_string($conexion, trim($product['nombre']));
    $marca = mysqli_real_escape_string($conexion, trim($product['marca']));
    $modelo = mysqli_real_escape_string($conexion, trim($product['modelo']));
    $precio = floatval($product['precio']);
    $unidades = intval($product['unidades']);
    $detalles = isset($product['detalles']) ? mysqli_real_escape_string($conexion, trim($product['detalles'])) : 'NA';
    $imagen = mysqli_real_escape_string($conexion, trim($product['imagen']));

    // Establecer charset UTF-8
    $conexion->set_charset("utf8");

    // Construir query de actualización
    $sql = "UPDATE productos SET 
            nombre = '{$nombre}', 
            marca = '{$marca}', 
            modelo = '{$modelo}', 
            precio = {$precio}, 
            detalles = '{$detalles}', 
            unidades = {$unidades}, 
            imagen = '{$imagen}' 
            WHERE id = {$id}";

    // Ejecutar query
    if($conexion->query($sql)) {
        // Verificar que realmente se actualizó un registro
        if($conexion->affected_rows > 0) {
            $data['status'] = 'success';
            $data['message'] = "Producto '{$nombre}' actualizado correctamente";
            $data['id'] = $id;
        } else {
            // No se realizaron cambios (los datos eran idénticos)
            $data['status'] = 'success';
            $data['message'] = 'Producto actualizado (sin cambios detectados)';
            $data['id'] = $id;
        }
    } else {
        $data['status'] = 'error';
        $data['message'] = 'Error al actualizar el producto: ' . mysqli_error($conexion);
    }

    // Cerrar conexión
    $conexion->close();
    
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>