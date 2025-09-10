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

<!-- ============================================================= -->
<h2>3) Evolución de tipos y contenidos (arrays, concatenación, aritmética)</h2>
<div class="box mono">
<?php
$a = "PHP5";
$z = []; 
$z[] = &$a;    // referencia al contenido de $a
$b = "5a version de PHP";
$c = $b*10;    // $b no es numérica pura → se convierte; "5a ..." => 5 * 10 = 50
echo "Tras \$c = \$b*10; \$c = "; var_dump($c);

$a .= $b;      // concatenación
echo "Tras \$a .= \$b; \$a = "; var_dump($a);

$b *= $c;      // "5a version..." → 5 * 50 = 250 (conversión numérica)
echo "Tras \$b *= \$c; \$b = "; var_dump($b);

$z[0] = "MySQL"; // rompe la referencia anterior, ahora z[0] es string independiente
echo "\$z = "; print_r($z);

unset($a,$b,$c,$z);
?>
</div>

<!-- ============================================================= -->
<h2>4) Lectura con $GLOBALS / global</h2>
<div class="box mono">
<?php
// Reconstruimos variables para la demostración con $GLOBALS
$a = "PHP5";
$GLOBALS['z'] = [];
$GLOBALS['z'][] = &$a;
$b = "5a version de PHP";
$c = $b*10;
$a .= $b;
$b *= $c;
$GLOBALS['z'][0] = "MySQL";

// Acceso con $GLOBALS:
echo "\$GLOBALS['a'] = "; var_dump($GLOBALS['a']);
echo "\$GLOBALS['b'] = "; var_dump($GLOBALS['b']);
echo "\$GLOBALS['c'] = "; var_dump($GLOBALS['c']);
echo "\$GLOBALS['z'] = "; print_r($GLOBALS['z']);

// Alternativa con 'global' dentro de una función:
function verConGlobal(){
  global $a,$b,$c,$z;
  echo "<br/>[global] a="; var_dump($a);
  echo "[global] b="; var_dump($b);
  echo "[global] c="; var_dump($c);
  echo "[global] z="; print_r($z);
}
verConGlobal();

unset($a,$b,$c,$z);
?>
</div>

<!-- ============================================================= -->
<h2>5) Casting y valores finales</h2>
<div class="box mono">
<?php
$a = "7 personas";
$b = (integer) $a; // => 7
$a = "9E3";        // notación científica -> 9000
$c = (double) $a;  // => 9000.0 (double)

echo "\$a = "; var_dump($a);
echo "\$b = "; var_dump($b);
echo "\$c = "; var_dump($c);

unset($a,$b,$c);
?>
</div>

<!-- ============================================================= -->
<h2>6) Valores booleanos y var_dump + conversión a string</h2>
<div class="box mono">
<?php
$a = "0";
$b = "TRUE";
$c = FALSE;
$d = ($a OR $b);
$e = ($a AND $c);
$f = ($a XOR $b);

echo "\$a = "; var_dump($a);
echo "\$b = "; var_dump($b);
echo "\$c = "; var_dump($c);
echo "\$d = "; var_dump($d);
echo "\$e = "; var_dump($e);
echo "\$f = "; var_dump($f);

// Transformar booleanos $c y $e a texto para echo:
function boolToText($v){ return $v ? 'true' : 'false'; }

echo "<br/>echo de \$c → " . boolToText($c) . "<br/>";
echo "echo de \$e → " . boolToText($e) . "<br/>";

unset($a,$b,$c,$d,$e,$f);
?>
</div>

<!-- ============================================================= -->
<h2>7) $_SERVER: versiones, SO del servidor e idioma del navegador</h2>
<div class="box mono">
<?php
$apache = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'N/D';
$phpv   = 'PHP ' . PHP_VERSION;
$so     = PHP_OS_FAMILY . " (" . PHP_OS . ")";
$lang   = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'N/D';

echo "Apache/Server: " . htmlspecialchars($apache, ENT_QUOTES, 'UTF-8') . "<br/>";
echo "PHP: " . htmlspecialchars($phpv, ENT_QUOTES, 'UTF-8') . "<br/>";
echo "Sistema operativo (servidor): " . htmlspecialchars($so, ENT_QUOTES, 'UTF-8') . "<br/>";
echo "Idioma del navegador (cliente): " . htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') . "<br/>";
?>
</div>

<p class="mono">Fin de P05 • <?= date('c'); ?></p>
</body>
</html>
