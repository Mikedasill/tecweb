<?php
include_once __DIR__.'/database.php';

echo "Conexión: ";
var_dump($conexion);
echo "\n\n";

// Test de inserción simple
$sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
        VALUES ('Test Product', 'Test Brand', 'TST-001', 10.50, 'Test details', 5, 'img/default.png', 0)";

if($conexion->query($sql)) {
    echo "✓ Inserción exitosa\n";
    echo "ID insertado: " . $conexion->insert_id;
} else {
    echo "✗ Error: " . mysqli_error($conexion);
}

$conexion->close();
?>