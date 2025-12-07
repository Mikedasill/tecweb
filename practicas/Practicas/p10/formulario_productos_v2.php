<?php
// formulario_productos_v2.php

$id = $_GET['id'] ?? null;
$producto = null;

if ($id) {
    @$link = new mysqli('localhost', 'root', 'Ubuntu12', 'marketzone');
    if ($link->connect_errno) {
        die('Falló la conexión: ' . $link->connect_error);
    }

    $id = intval($id);
    $result = $link->query("SELECT * FROM productos WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $producto = $result->fetch_assoc();
    } else {
        die('Producto no encontrado.');
    }
    $link->close();
} else {
    die('ID de producto no especificado.');
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Editar Producto - Tienda de Electrónicos</title>
    <style type="text/css">
        ol, ul { list-style-type: none; }
        .error { color: red; font-size: 0.9em; margin-top: 0.2em; }
        fieldset { max-width: 600px; }
        input, textarea, select {
            width: 300px;
            padding: 5px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Editar Producto - Electrónicos</h1>

    <form id="formProducto" action="update_producto.php" method="post">

        <!-- Campo oculto para enviar el ID -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">

        <fieldset>
            <legend>Datos del Producto</legend>

            <ul>
                <li>
                    <label for="nombre">Nombre del producto:</label>
                    <input type="text" name="nombre" id="nombre" maxlength="100" 
                           value="<?= htmlspecialchars($producto['nombre']) ?>">
                    <div id="errorNombre" class="error"></div>
                </li>

                <li>
                    <label for="marca">Marca:</label>
                    <select name="marca" id="marca">
                        <option value="">-- Seleccione una marca --</option>
                        <?php
                        $marcas = ['Sony', 'Samsung', 'Apple', 'Logitech', 'Dell', 'HP', 'Xiaomi', 'Lenovo', 'Otra'];
                        foreach ($marcas as $m) {
                            $selected = ($m === $producto['marca']) ? 'selected' : '';
                            echo "<option value=\"$m\" $selected>$m</option>";
                        }
                        ?>
                    </select>
                    <div id="errorMarca" class="error"></div>
                </li>

                <li>
                    <label for="modelo">Modelo:</label>
                    <input type="text" name="modelo" id="modelo" maxlength="25"
                           value="<?= htmlspecialchars($producto['modelo']) ?>">
                    <div id="errorModelo" class="error"></div>
                </li>

                <li>
                    <label for="precio">Precio:</label>
                    <input type="number" name="precio" id="precio" step="0.01" min="0"
                           value="<?= htmlspecialchars($producto['precio']) ?>">
                    <div id="errorPrecio" class="error"></div>
                </li>

                <li>
                    <label for="detalles">Detalles:</label><br>
                    <textarea name="detalles" id="detalles" rows="4" cols="50" maxlength="250"><?= htmlspecialchars($producto['detalles']) ?></textarea>
                    <div id="errorDetalles" class="error"></div>
                </li>

                <li>
                    <label for="unidades">Unidades en stock:</label>
                    <input type="number" name="unidades" id="unidades" min="0"
                           value="<?= htmlspecialchars($producto['unidades']) ?>">
                    <div id="errorUnidades" class="error"></div>
                </li>

                <li>
                    <label for="imagen">Ruta de la imagen (opcional):</label>
                    <input type="text" name="imagen" id="imagen" maxlength="100"
                           value="<?= htmlspecialchars($producto['imagen'] ?: 'img/default.png') ?>">
                    <div id="errorImagen" class="error"></div>
                </li>
            </ul>
        </fieldset>

        <p>
            <input type="submit" value="Actualizar Producto">
            <input type="reset" value="Limpiar">
        </p>

    </form>

    <script>
        document.getElementById('formProducto').addEventListener('submit', function(e) {
            const errores = document.querySelectorAll('.error');
            errores.forEach(el => el.textContent = '');
            let valido = true;

            // Validaciones (mismas del formulario original)
            const nombre = document.getElementById('nombre').value.trim();
            if (nombre === '') {
                document.getElementById('errorNombre').textContent = 'El nombre es obligatorio.';
                valido = false;
            } else if (nombre.length > 100) {
                document.getElementById('errorNombre').textContent = 'El nombre no debe exceder 100 caracteres.';
                valido = false;
            }

            const marca = document.getElementById('marca').value;
            if (marca === '') {
                document.getElementById('errorMarca').textContent = 'Debe seleccionar una marca.';
                valido = false;
            }

            const modelo = document.getElementById('modelo').value.trim();
            if (modelo === '') {
                document.getElementById('errorModelo').textContent = 'El modelo es obligatorio.';
                valido = false;
            } else if (modelo.length > 25) {
                document.getElementById('errorModelo').textContent = 'El modelo no debe exceder 25 caracteres.';
                valido = false;
            } else if (!/^[a-zA-Z0-9]+$/.test(modelo)) {
                document.getElementById('errorModelo').textContent = 'El modelo solo puede contener letras y números.';
                valido = false;
            }

            const precioStr = document.getElementById('precio').value.trim();
            const precio = parseFloat(precioStr);
            if (precioStr === '' || isNaN(precio)) {
                document.getElementById('errorPrecio').textContent = 'El precio es obligatorio y debe ser un número válido.';
                valido = false;
            } else if (precio <= 99.99) {
                document.getElementById('errorPrecio').textContent = 'El precio debe ser mayor a 99.99.';
                valido = false;
            }

            const detalles = document.getElementById('detalles').value;
            if (detalles.length > 250) {
                document.getElementById('errorDetalles').textContent = 'Los detalles no deben exceder 250 caracteres.';
                valido = false;
            }

            const unidadesStr = document.getElementById('unidades').value.trim();
            const unidades = parseInt(unidadesStr, 10);
            if (unidadesStr === '' || isNaN(unidades)) {
                document.getElementById('errorUnidades').textContent = 'Las unidades son obligatorias y deben ser un número entero.';
                valido = false;
            } else if (unidades < 0) {
                document.getElementById('errorUnidades').textContent = 'Las unidades deben ser mayor o igual a 0.';
                valido = false;
            }

            const imagenInput = document.getElementById('imagen');
            if (imagenInput.value.trim() === '') {
                imagenInput.value = 'img/default.png';
            }

            if (!valido) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>