<?php
// update_producto.php

// Recibir datos del formulario
$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$marca = $_POST['marca'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$precio = $_POST['precio'] ?? '';
$detalles = $_POST['detalles'] ?? '';
$unidades = $_POST['unidades'] ?? '';
$imagen = $_POST['imagen'] ?? 'img/default.png';

// Validar que el ID exista
if (!$id) {
    die('Error: ID del producto no proporcionado.');
}

// Conexión a la base de datos
@$link = new mysqli('localhost', 'root', 'Ubuntu12', 'marketzone');

if ($link->connect_errno) {
    die('Falló la conexión: ' . $link->connect_error);
}

// Preparar la consulta UPDATE (¡usar sentencias preparadas es ideal para evitar inyecciones!)
// Pero por simplicidad y coherencia con el estilo del ejercicio, usamos interpolación con validación previa.

// Validar que los valores numéricos sean seguros
$precio = floatval($precio);
$unidades = intval($unidades);
$id = intval($id);

// Escapar valores para evitar inyección SQL básica (mejor sería usar prepared statements)
$nombre = $link->real_escape_string($nombre);
$marca = $link->real_escape_string($marca);
$modelo = $link->real_escape_string($modelo);
$detalles = $link->real_escape_string($detalles);
$imagen = $link->real_escape_string($imagen);

$sql = "UPDATE productos 
        SET nombre='$nombre', marca='$marca', modelo='$modelo', 
            precio=$precio, detalles='$detalles', unidades=$unidades, imagen='$imagen'
        WHERE id = $id";

if ($link->query($sql)) {
    echo "<script>
            alert('Producto actualizado correctamente.');
            window.location.href = 'get_productos_vigentes_v2.php';
          </script>";
} else {
    echo "ERROR: No se pudo ejecutar la consulta. " . $link->error;
}

$link->close();
?>