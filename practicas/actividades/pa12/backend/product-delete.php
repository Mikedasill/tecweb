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

    // Validar que el ID sea un número entero válido
    $id = $_POST['id'];
    if(!is_numeric($id) || intval($id) <= 0) {
        $data['status'] = 'error';
        $data['message'] = 'ID de producto inválido';
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // Convertir a entero para mayor seguridad
    $id = intval($id);

    // Verificar que el producto existe y no está eliminado
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
        $data['message'] = 'El producto no existe o ya fue eliminado';
        $resultCheck->free();
        $conexion->close();
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // Obtener el nombre del producto para el mensaje
    $producto = $resultCheck->fetch_assoc();
    $nombreProducto = $producto['nombre'];
    $resultCheck->free();

    // SE REALIZA EL BORRADO LÓGICO (UPDATE eliminado=1)
    $conexion->set_charset("utf8");
    $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
    
    if($conexion->query($sql)) {
        // Verificar que realmente se actualizó un registro
        if($conexion->affected_rows > 0) {
            $data['status'] = 'success';
            $data['message'] = "Producto '{$nombreProducto}' eliminado correctamente";
            $data['id'] = $id;
        } else {
            $data['status'] = 'warning';
            $data['message'] = 'No se realizaron cambios en el producto';
        }
    } else {
        $data['status'] = 'error';
        $data['message'] = 'Error al eliminar el producto: ' . mysqli_error($conexion);
    }

    // Cerrar conexión
    $conexion->close();
    
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>