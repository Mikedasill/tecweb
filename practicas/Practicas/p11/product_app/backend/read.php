<?php
include_once __DIR__.'/database.php';

// SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
$data = array();

// NUEVO: Verificar si se recibió un término de búsqueda
if (isset($_POST['query'])) {
    $query = $_POST['query'];

    // Escapar comillas simples para evitar inyección SQL básica (nivel académico)
    $query = mysqli_real_escape_string($conexion, $query);

    // NUEVA QUERY: buscar en nombre, marca o detalles con LIKE
    $sql = "SELECT * FROM productos 
            WHERE nombre LIKE '%{$query}%' 
               OR marca LIKE '%{$query}%' 
               OR detalles LIKE '%{$query}%'";

    if ($result = $conexion->query($sql)) {
        // Recorrer todos los resultados y agregarlos al array
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $producto = array();
            foreach ($row as $key => $value) {
                $producto[$key] = utf8_encode($value);
            }
            $data[] = $producto; // Agregar producto al array principal
        }
        $result->free();
    } else {
        die('Query Error: ' . mysqli_error($conexion));
    }
    $conexion->close();
}

// Devolver siempre un array JSON (aunque esté vacío)
echo json_encode($data, JSON_PRETTY_PRINT);
?>