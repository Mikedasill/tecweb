<?php
// practicas/p10/formulario_productos_v2.php
require_once __DIR__ . '/conexion.php';

// Valores por defecto
$prod = [
  'id' => '', 'nombre' => '', 'modelo' => '', 'marca' => '',
  'precio' => '', 'existencias' => '', 'descripcion' => '', 'imagen_url' => ''
];

// Si llega ?id= precargamos para editar
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
  $id = (int)$_GET['id'];
  $st = $mysqli->prepare("SELECT id,nombre,modelo,marca,precio,existencias,descripcion,imagen_url FROM productos WHERE id=?");
  $st->bind_param("i", $id);
  $st->execute();
  if ($row = $st->get_result()->fetch_assoc()) { $prod = $row; }
  $st->close();
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title><?= $prod['id'] ? "Editar producto #{$prod['id']}" : "Alta de producto (v2)" ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  :root { --b:#111; --g:#0a7a0a; --r:#b00020; --bd:#e5e7eb; }
  body{font-family:system-ui,Segoe UI,Arial;margin:24px;max-width:960px}
  h1{margin:0 0 16px}
  form{display:grid;gap:12px}
  .grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  label{font-weight:600;margin:.4rem 0 .2rem}
  input,select,textarea{width:100%;padding:.55rem;border:1px solid var(--bd);border-radius:8px}
  textarea{resize:vertical}
  .error{color:var(--r);font-size:.92rem;min-height:1.1rem}
  .muted{color:#666}
  .actions{display:flex;gap:10px;align-items:center}
  button{background:var(--b);color:#fff;border:0;border-radius:10px;padding:.6rem 1rem;cursor:pointer}
  a.btn{padding:.55rem .9rem;border:1px solid var(--bd);border-radius:10px;text-decoration:none;color:#111;background:#fff}
</style>
</head>
<body>
  <h1><?= $prod['id'] ? "Editar producto #{$prod['id']}" : "Alta de producto (v2)" ?></h1>

  <form id="form" action="update_producto.php" method="post" novalidate>
    <input type="hidden" name="id" value="<?= htmlspecialchars($prod['id']) ?>">

    <div class="grid">
      <div>
        <label for="nombre">Nombre *</label>
        <input id="nombre" name="nombre" maxlength="100" value="<?= htmlspecialchars($prod['nombre']) ?>">
        <div class="error" id="e-nombre"></div>
      </div>
      <div>
        <label for="modelo">Modelo * (alfanumérico, ≤25)</label>
        <input id="modelo" name="modelo" maxlength="25" value="<?= htmlspecialchars($prod['modelo']) ?>">
        <div class="error" id="e-modelo"></div>
      </div>
    </div>

    <div class="grid">
      <div>
        <label for="marca">Marca *</label>
        <select id="marca" name="marca">
          <option value="">— Selecciona —</option>
          <?php
            foreach (['Nike','Adidas','Puma','Apple','Samsung','Sony'] as $m) {
              $sel = ($prod['marca']===$m)?'selected':'';
              echo "<option $sel>".htmlspecialchars($m)."</option>";
            }
          ?>
        </select>
        <div class="error" id="e-marca"></div>
      </div>
      <div>
        <label for="precio">Precio (MXN) * (> 99.99)</label>
        <input id="precio" name="precio" type="number" step="0.01" min="0" value="<?= htmlspecialchars($prod['precio']) ?>">
        <div class="error" id="e-precio"></div>
      </div>
    </div>

    <div class="grid">
      <div>
        <label for="existencias">Existencias * (entero ≥ 0)</label>
        <input id="existencias" name="existencias" type="number" step="1" min="0" value="<?= htmlspecialchars($prod['existencias']) ?>">
        <div class="error" id="e-existencias"></div>
      </div>
      <div>
        <label for="imagen_url">URL de imagen (opcional)</label>
        <input id="imagen_url" name="imagen_url" placeholder="https://..." value="<?= htmlspecialchars($prod['imagen_url']) ?>">
        <div class="error" id="e-imagen"></div>
      </div>
    </div>

    <div>
      <label for="descripcion">Detalles (opcional, ≤ 250)</label>
      <<textarea id="descripcion" name="descripcion" maxlength="1000"><?= htmlspecialchars($prod['descripcion']) ?></textarea>
      <div class="error" id="e-desc"></div>
    </div>

    <p class="muted">* Campos obligatorios</p>
    <div class="actions">
      <button type="submit"><?= $prod['id'] ? "Actualizar" : "Guardar" ?></button>
      <a class="btn" href="get_productos_xhtml_v2.php">Listado</a>
      <a class="btn" href="get_productos_vigentes_v2.php">Vigentes</a>
    </div>
  </form>

<script>
// ------ VALIDACIONES EN CLIENTE ------
const $ = s => document.querySelector(s);
const setErr = (id,msg='') => { const n = document.getElementById(id); if(n) n.textContent = msg; };
const isAlnum = s => /^[\p{L}\p{N}\s\-_.]+$/u.test(s);

document.getElementById('form').addEventListener('submit', (ev)=>{
  let ok = true;

  // nombre (requerido, <=100)
  const nombre = $('#nombre').value.trim();
  setErr('e-nombre');
  if (!nombre || nombre.length>100){ setErr('e-nombre','Requerido, máximo 100.'); ok=false; }

  // modelo (requerido, alfanumérico, <=25)
  const modelo = $('#modelo').value.trim();
  setErr('e-modelo');
  if (!modelo || modelo.length>25 || !isAlnum(modelo)){
    setErr('e-modelo','Requerido, alfanumérico y ≤25.'); ok=false;
  }

  // marca (select)
  const marca = $('#marca').value.trim();
  setErr('e-marca');
  if (!marca){ setErr('e-marca','Selecciona una marca.'); ok=false; }

  // precio (> 99.99)
  const precio = parseFloat($('#precio').value);
  setErr('e-precio');
  if (isNaN(precio) || precio <= 99.99){ setErr('e-precio','Debe ser mayor a 99.99.'); ok=false; }

  // existencias (entero >= 0)
  const ex = $('#existencias').value;
  setErr('e-existencias');
  if (ex === '' || !Number.isInteger(Number(ex)) || Number(ex) < 0){
    setErr('e-existencias','Entero ≥ 0.'); ok=false;
  }

  // descripción (≤250)
  const desc = $('#descripcion').value;
  setErr('e-desc');
  if (desc.length > 250){ setErr('e-desc','Máximo 250 caracteres.'); ok=false; }

  if (!ok) ev.preventDefault();
});
</script>
</body>
</html>
