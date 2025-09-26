<?php
// practicas/p07/index.php
declare(strict_types=1);
header('Content-Type: text/html; charset=utf-8');

require_once __DIR__ . '/src/src.php';

// Router por query-string (vista): '1'..'6' o 'todo'
$ej = $_GET['ej'] ?? 'todo';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>P07 | Funciones, ciclos y arreglos en PHP (E1–E6)</title>
  <style>
    :root{ color-scheme: light dark; }
    body{ font-family: system-ui, Segoe UI, Roboto, Arial, sans-serif; margin:28px; line-height:1.4 }
    header, footer{ margin:0 0 16px 0 }
    h1{ font-size:1.55rem; margin:0 0 8px }
    h2{ font-size:1.15rem; margin:24px 0 8px }
    nav a{ display:inline-block; padding:.35rem .6rem; border:1px solid #bbb; border-radius:.5rem; margin:.1rem .2rem; text-decoration:none }
    table{ border-collapse:collapse; }
    th,td{ border:1px solid #ccc; padding:.35rem .5rem; }
    code,kbd,pre{ font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
    pre{ background:#f3f3f3; padding:10px; overflow:auto }
    .mono{ font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; }
    .muted{ color:#666 } .ok{ color:#067d00 } .bad{ color:#b00020 }
    .chip{ display:inline-block; padding:.05rem .35rem; border:1px solid #bbb; border-radius:.4rem; font-size:.85em }
    .row-ok{ background:#eafbe7 }
    form{ margin:.3rem 0 }
  </style>
</head>
<body>

<header>
  <h1>Práctica 07 — Funciones, ciclos y arreglos en PHP (E1–E6)</h1>
  <nav>
    <strong>Ver:</strong>
    <a href="?ej=todo">Todo</a>
    <a href="?ej=1">EJ 1</a>
    <a href="?ej=2">EJ 2</a>
    <a href="?ej=3">EJ 3</a>
    <a href="?ej=4">EJ 4</a>
    <a href="?ej=5">EJ 5</a>
    <a href="?ej=6">EJ 6</a>
  </nav>
</header>

<main>
<?php
switch ($ej) {
  case '1': echo renderEj1(); break;
  case '2': echo renderEj2(); break;
  case '3': echo renderEj3(); break;
  case '4': echo renderEj4(); break;
  case '5': echo renderEj5(); break;
  case '6': echo renderEj6(); break;

  case 'todo':
  default:
    echo renderEj1();  echo "<hr>";
    echo renderEj2();  echo "<hr>";
    echo renderEj3();  echo "<hr>";
    echo renderEj4();  echo "<hr>";
    echo renderEj5();  echo "<hr>";
    echo renderEj6();  // sin cortar la página
    break;
}
?>
</main>

<footer>
  <p class="muted">Fin de P07 • <span class="ok"><?= e(date('c')) ?></span></p>
</footer>

</body>
</html>





