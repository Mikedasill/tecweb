<?php
require_once __DIR__ . '/conexion.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { die('Falta parámetro id. Abre desde el listado con ?id='); }

// Consulta del producto
$stmt = $mysqli->prepare(
  "SELECT id,nombre,modelo,marca,precio,existencias,descripcion,imagen_url,eliminado
   FROM productos WHERE id=?"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$prod = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$prod) { die('No existe el producto con id='.$id); }

// Helpers
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<meta charset="utf-8">
<title>Editar producto (v3)</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
<main class="container">
  <h2>Editar producto (v3)</h2>
  <form action="update_producto.php" method="post" id="frm">
    <input type="hidden" name="id" value="<?= $prod['id'] ?>">

    <div class="grid">
      <label>Nombre *
        <input name="nombre" id="nombre" maxlength="100" value="<?= h($prod['nombre']) ?>" required>
        <small>Requerido, máximo 100.</small>
      </label>
      <label>Modelo * (alfanumérico, ≤25)
        <input name="modelo" id="modelo" maxlength="25" value="<?= h($prod['modelo']) ?>" required>
        <small>Solo letras y números.</small>
      </label>
    </div>

    <div class="grid">
      <label>Marca *
        <input name="marca" id="marca" maxlength="50" value="<?= h($prod['marca']) ?>" required>
      </label>
      <label>Precio (MXN) * (> 99.99)
        <input type="number" step="0.01" min="0" name="precio" id="precio" value="<?= h($prod['precio']) ?>" required>
      </label>
    </div>

    <div class="grid">
      <label>Existencias * (entero ≥ 0)
        <input type="number" step="1" min="0" name="existencias" id="existencias" value="<?= h($prod['existencias']) ?>" required>
      </label>
      <label>URL de imagen (opcional)
        <input name="imagen_url" id="imagen_url" placeholder="https://..." value="<?= h($prod['imagen_url']) ?>">
        <small>Si la dejas vacía se usará <code>img/default.png</code>.</small>
      </label>
    </div>

    <label>Detalles (opcional, ≤ 250)
      <textarea name="descripcion" id="descripcion" maxlength="250"><?= h($prod['descripcion']) ?></textarea>
    </label>

    <label>
      <input type="checkbox" name="eliminado" value="1" <?= ((int)$prod['eliminado']===1?'checked':'') ?>>
      Marcar como eliminado (no vigente)
    </label>

    <button type="submit">Actualizar</button>
    <a class="secondary" href="get_productos_xhtml_v2.php">Volver al listado</a>
  </form>
</main>

<script>
  // Validaciones mínimas en cliente (como v2)
  const reAlnum = /^[a-zA-Z0-9\-_. ]+$/;
  document.getElementById('frm').addEventListener('submit', (e)=>{
    const nombre = document.getElementById('nombre').value.trim();
    const modelo = document.getElementById('modelo').value.trim();
    const precio = parseFloat(document.getElementById('precio').value);
    const exist  = parseInt(document.getElementById('existencias').value);

    if (!nombre || nombre.length>100) { alert('Nombre requerido (≤100).'); e.preventDefault(); return; }
    if (!modelo || !reAlnum.test(modelo) || modelo.length>25) { alert('Modelo alfanumérico (≤25).'); e.preventDefault(); return; }
    if (!(precio>99.99)) { alert('Precio debe ser > 99.99'); e.preventDefault(); return; }
    if (!(exist>=0)) { alert('Existencias debe ser entero ≥ 0'); e.preventDefault(); return; }
  });
</script>
