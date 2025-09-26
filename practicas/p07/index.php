<?php
// practicas/p07/index.php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');

// Carga funciones y datos
require_once __DIR__ . '/src/src.php';

// Router muy simple por query-string
$ej  = $_GET['ej']  ?? 'todo';   // '1'..'7' | 'todo'
$mat = $_GET['mat'] ?? null;     // matrícula para el ej. 6 (detalle)
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>p07 | Funciones, GET/POST, ciclos y arreglos en PHP</title>
  <style>
    :root{ color-scheme: light dark; }
    body{ font-family: system-ui, Segoe UI, Roboto, Arial, sans-serif; line-height:1.35; margin: 28px; }
    header, footer{ margin: 0 0 16px 0; }
    h1{ font-size: 1.6rem; margin: 0 0 8px 0; }
    h2{ font-size: 1.2rem; margin: 24px 0 6px 0; }
    nav a{ display:inline-block; padding:.35rem .6rem; border:1px solid #bbb; border-radius:.5rem; margin:.15rem .25rem; text-decoration:none }
    code, kbd, pre { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
    pre{ background:#f3f3f3; padding:10px; overflow:auto; }
    table{ border-collapse:collapse; }
    td, th{ border:1px solid #ccc; padding:.35rem .5rem; }
    .muted{ color:#666 }
    .hint{ color:#666; font-size:.9rem }
    .ok{ color: #067d00; }
  </style>
</head>
<body>

<header>
  <h1>Práctica 07 — Funciones, ciclos y arreglos (PHP)</h1>
  <nav>
    <strong>Ver:</strong>
    <a href="?ej=todo">Todo</a>
    <a href="?ej=1">EJ 1</a>
    <a href="?ej=2">EJ 2</a>
    <a href="?ej=3">EJ 3</a>
    <a href="?ej=4">EJ 4</a>
    <a href="?ej=5">EJ 5</a>
    <a href="?ej=6">EJ 6</a>
    <a href="?ej=7">EJ 7</a>
  </nav>
  <p class="hint">Tip: en el Ejercicio 6 puedes abrir el detalle con <code>?ej=6&amp;mat=NOP4568</code>.</p>
</header>

<main>
<?php
switch ($ej) {
  case '1':
    echo renderEj1();
    break;

  case '2':
    echo renderEj2();
    break;

  case '3':
    echo renderEj3();
    break;

  case '4':
    echo renderEj4();
    break;

  case '5':
    echo renderEj5();
    break;

  case '6':
    echo renderEj6($mat);
    break;

  case '7':
    echo renderEj7();
    break;

  case 'todo':
  default:
    echo renderEj1();
    echo "<hr>";
    echo renderEj2();
    echo "<hr>";
    echo renderEj3();
    echo "<hr>";
    echo renderEj4();
    echo "<hr>";
    echo renderEj5();
    echo "<hr>";
    echo renderEj6($mat);
    echo "<hr>";
    echo renderEj7();
    break;
}
?>
</main>

<footer>
  <p class="muted">Fin de P07 • <span class="ok"><?= e(date('c')) ?></span></p>
</footer>

</body>
</html>




