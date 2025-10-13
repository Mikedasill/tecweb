<?php
header('Content-Type: text/html; charset=utf-8');

// Detecta método y toma variables con fallback
$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Acepta name/age o nombre/edad
$nombre = $_GET['name']   ?? $_POST['name']   ?? $_GET['nombre'] ?? $_POST['nombre'] ?? '';
$edad   = $_GET['age']    ?? $_POST['age']    ?? $_GET['edad']   ?? $_POST['edad']   ?? '';

// Sanitiza para imprimir en HTML
$nombreH = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
$edadH   = htmlspecialchars($edad,   ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<meta charset="utf-8">
<title>formulario.php</title>

<h1>Datos Personales</h1>
<fieldset>
  <legend>Actualiza los datos personales de esta persona:</legend>

  <p><label>Nombre:
    <input type="text" value="<?= $nombreH ?>">
  </label></p>

  <p><label>Edad:
    <input type="text" value="<?= $edadH ?>">
  </label></p>

  <button>ENVIAR</button>
</fieldset>

<hr>
<h3>Depuración</h3>
<p>Método recibido: <strong><?= htmlspecialchars($metodo) ?></strong></p>
<h4>$_GET</h4>
<pre><?php print_r($_GET); ?></pre>
<h4>$_POST</h4>
<pre><?php print_r($_POST); ?></pre>

<p>
  <a href="get_row_action.html">Volver a GET</a> ·
  <a href="post_row_action.html">Volver a POST</a>
</p>
