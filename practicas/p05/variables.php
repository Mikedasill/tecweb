<?php
/* practicas/p05/variables.php */
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Mexico_City');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>P05 – Manejo de variables en PHP</title>
  <style type="text/css">
    body{font-family:Segoe UI,Arial,sans-serif;margin:20px;line-height:1.5}
    code{background:#f5f5f5;padding:2px 4px;border-radius:3px}
    h1{border-bottom:1px solid #57b1dc;padding-bottom:.3rem}
    h2{color:#d1633c;margin-top:2rem}
    .box{background:#fafafa;border:1px solid #ddd;border-radius:6px;padding:12px;margin:10px 0}
    .mono{font-family:Consolas,monospace}
  </style>
</head>
<body>

<h1>Práctica 5 – Manejo de variables en PHP</h1>
<p><small>Servidor: <?= htmlspecialchars(PHP_VERSION . " / " . PHP_SAPI, ENT_QUOTES, 'UTF-8'); ?> — Fecha: <?= date('Y-m-d H:i:s'); ?></small></p>

<!-- ============================================================= -->
<h2>1) Variables válidas e inválidas</h2>
<div class="box mono">
<?php
/*
  Lista a evaluar: $_myvar, $_7var, myvar, $myvar, $var7, $_element1, $house*5
  Reglas: 
  - Deben comenzar con $.
  - Primer carácter tras $: letra o guion bajo.
  - No se permiten símbolos como * en el nombre.
*/
$explicacion = [
  '$_myvar'   => 'Válida: empieza con $ y el nombre inicia con _ (permitido).',
  '$_7var'    => 'Válida: empieza con $ y el nombre inicia con _ (permitido), números después OK.',
  'myvar'     => 'Inválida: falta el signo $.',
  '$myvar'    => 'Válida: empieza con $ y luego letras.',
  '$var7'     => 'Válida: números permitidos después del primer carácter.',
  '$_element1'=> 'Válida: guion bajo + letras/números.',
  '$house*5'  => 'Inválida: el * no puede formar parte del nombre de variable.'
];

foreach ($explicacion as $var => $why) {
  echo $var . " → " . htmlspecialchars($why, ENT_QUOTES, 'UTF-8') . "<br/>";
}
unset($explicacion);
?>
</div>
<!-- ============================================================= -->
<h2>2) Referencias y reasignaciones ($a, $b, $c)</h2>
<div class="box mono">
<?php
$a = "ManejadorSQL";
$b = 'MySQL';
$c = &$a; // c referencia a a

echo "<strong>Bloque 1</strong><br/>";
echo "\$a = "; var_dump($a);
echo "\$b = "; var_dump($b);
echo "\$c = "; var_dump($c);

echo "<br/><strong>Bloque 2 (nuevas asignaciones)</strong><br/>";
$a = "PHP server";
$b = &$a; // ahora b referencia a a
echo "\$a = "; var_dump($a);
echo "\$b = "; var_dump($b);
echo "\$c = "; var_dump($c);

echo "<br/><strong>Descripción:</strong> En el segundo bloque, al hacer <code>\$b =& \$a</code>, \$b y \$a apuntan a la MISMA referencia. "
   . "Cambiar \$a cambiaría también \$b automáticamente; \$c seguía referenciando a \$a desde el primer bloque, así que también refleja el nuevo contenido.";
unset($a,$b,$c);
?>
</div>

