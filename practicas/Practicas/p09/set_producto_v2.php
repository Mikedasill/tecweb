<?php
/** Conexión a la base de datos */
@$link = new mysqli('localhost', 'root', 'Ubuntu12', 'marketzone');

if ($link->connect_errno) {
    die('Falló la conexión: ' . $link->connect_error);
}

/** Recibir datos del formulario */
$nombre   = trim($_POST['nombre'] ?? '');
$marca    = trim($_POST['marca'] ?? '');
$modelo   = trim($_POST['modelo'] ?? '');
$precio   = floatval($_POST['precio'] ?? 0);
$detalles = trim($_POST['detalles'] ?? '');
$unidades = intval($_POST['unidades'] ?? 0);
$imagen   = trim($_POST['imagen'] ?? 'img/default.png'); // valor por defecto
$eliminado = 0;

/** Validar que los campos requeridos no estén vacíos */
if (empty($nombre) || empty($marca) || empty($modelo)) {
    die('<h1>Error: Datos incompletos</h1><p>Los campos Nombre, Marca y Modelo son obligatorios.</p>');
}

/** Validar que no exista ya un producto con esta combinación (nombre + marca + modelo) */
$sql_check = "SELECT id FROM productos WHERE nombre = ? AND marca = ? AND modelo = ?";
$stmt = $link->prepare($sql_check);
$stmt->bind_param("sss", $nombre, $marca, $modelo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die('<h1>Error: Producto duplicado</h1><p>Ya existe un producto con ese nombre, marca y modelo.</p>');
}


/** Insertar nuevo producto */
$sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $link->prepare($sql_insert);
$stmt_insert->bind_param("sssdssi", $nombre, $marca, $modelo, $precio, $detalles, $unidades, $imagen);

if ($stmt_insert->execute()) {
    $nuevo_id = $link->insert_id;
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Producto Registrado</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #e8f5e9; }
            h1 { color: #007B43; }
            .success { background: #dcedc8; padding: 20px; border-radius: 8px; }
        </style>
    </head>
    <body>
        <h1>✅ Producto registrado con éxito</h1>
        <div class="success">
            <p><strong>ID:</strong> ' . htmlspecialchars($nuevo_id) . '</p>
            <p><strong>Nombre:</strong> ' . htmlspecialchars($nombre) . '</p>
            <p><strong>Marca:</strong> ' . htmlspecialchars($marca) . '</p>
            <p><strong>Modelo:</strong> ' . htmlspecialchars($modelo) . '</p>
            <p><strong>Precio:</strong> $' . number_format($precio, 2) . '</p>
            <p><strong>Unidades:</strong> ' . htmlspecialchars($unidades) . '</p>
            <p><strong>Imagen:</strong> ' . htmlspecialchars($imagen) . '</p>
            <p><strong>Eliminado:</strong> 0</p>
            <p><strong>Detalles:</strong> ' . htmlspecialchars($detalles) . '</p>
        </div>
        <br>
        <a href="formulario_productos.html">← Registrar otro producto</a>
    </body>
    </html>';
} else {
    die('<h1>Error al registrar producto</h1><p>No se pudo insertar el producto en la base de datos.</p>');
}

$link->close();
?>