<?php
// practicas/p07/src/src.php
declare(strict_types=1);

// Zona horaria (por si la del php.ini no ha cargado)
date_default_timezone_set('America/Mexico_City');

/* =========================================================
 * Helpers
 * =======================================================*/

/** Escapa HTML de forma segura */
function e(string $text): string {
  return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/** Envuelve un título de sección */
function h2(string $title): string {
  return "<h2>" . e($title) . "</h2>";
}

/* =========================================================
 * Datos y funciones de negocio
 * =======================================================*/

/** Parque vehicular (arreglo asociativo por matrícula) */
function parqueVehicular(): array {
  return [
    'NOP4568' => [
      'auto' => ['marca' => 'HONDA',  'modelo' => 2014, 'tipo' => 'hatchback'],
      'propietario' => ['nombre' => 'Oscar Paz', 'ciudad' => 'Monterrey',  'direccion' => 'San Pedro'],
    ],
    'OPS0679' => [
      'auto' => ['marca' => 'TOYOTA', 'modelo' => 2013, 'tipo' => 'camioneta'],
      'propietario' => ['nombre' => 'Lola Cruz', 'ciudad' => 'Guadalajara', 'direccion' => 'Providencia'],
    ],
    'ABZ1234' => [
      'auto' => ['marca' => 'NISSAN', 'modelo' => 2019, 'tipo' => 'sedán'],
      'propietario' => ['nombre' => 'Diego León', 'ciudad' => 'CDMX', 'direccion' => 'Roma Norte'],
    ],
  ];
}

/** Busca vehículo por matrícula (insensible a minúsculas) */
function buscarPorMatricula(array $parque, string $matricula): ?array {
  $key = strtoupper(trim($matricula));
  return $parque[$key] ?? null;
}

/* =========================================================
 * Renderizadores de ejercicios (devuelven HTML)
 * =======================================================*/

function renderEj1(): string {
  // For simple
  $n = max(1, (int)($_GET['n'] ?? 10));
  $html  = h2("Ejercicio 1 · Secuencia del 1 al {$n}");
  $html .= "<ol>";
  for ($i = 1; $i <= $n; $i++) {
    $html .= "<li>" . e((string)$i) . "</li>";
  }
  $html .= "</ol>";
  return $html;
}

function renderEj2(): string {
  // Tabla de multiplicar
  $t = max(1, (int)($_GET['t'] ?? 5));
  $html  = h2("Ejercicio 2 · Tabla del {$t}");
  $html .= "<table><thead><tr><th>Operación</th><th>Resultado</th></tr></thead><tbody>";
  for ($i = 1; $i <= 10; $i++) {
    $html .= "<tr><td>" . e("$t × $i") . "</td><td>" . e((string)($t*$i)) . "</td></tr>";
  }
  $html .= "</tbody></table>";
  return $html;
}

function renderEj3(): string {
  // foreach sobre lista simple
  $frutas = ['Manzana', 'Plátano', 'Mango', 'Fresa', 'Uva'];
  $html  = h2("Ejercicio 3 · Lista de frutas");
  $html .= "<ul>";
  foreach ($frutas as $f) {
    $html .= "<li>" . e($f) . "</li>";
  }
  $html .= "</ul>";
  return $html;
}

function renderEj4(): string {
  // foreach sobre arreglo asociativo (clave => valor)
  $paises = ['MX' => 'México', 'US' => 'Estados Unidos', 'CA' => 'Canadá'];
  $html  = h2("Ejercicio 4 · Países (clave → nombre)");
  $html .= "<table><thead><tr><th>Clave</th><th>País</th></tr></thead><tbody>";
  foreach ($paises as $clave => $nombre) {
    $html .= "<tr><td><code>" . e($clave) . "</code></td><td>" . e($nombre) . "</td></tr>";
  }
  $html .= "</tbody></table>";
  return $html;
}

function renderEj5(): string {
  // Estadísticas de un arreglo numérico
  $nums = [5, 9, 2, 11, 7, 3, 14];
  $count = count($nums);
  $min   = min($nums);
  $max   = max($nums);
  $prom  = array_sum($nums) / $count;
  $orden = $nums; sort($orden);

  $html  = h2("Ejercicio 5 · Estadísticas");
  $html .= "<p>Arreglo: <code>" . e(json_encode($nums)) . "</code></p>";
  $html .= "<ul>";
  $html .= "<li>Cantidad: <strong>" . e((string)$count) . "</strong></li>";
  $html .= "<li>Mínimo: <strong>" . e((string)$min)   . "</strong></li>";
  $html .= "<li>Máximo: <strong>" . e((string)$max)   . "</strong></li>";
  $html .= "<li>Promedio: <strong>" . e(number_format($prom, 2)) . "</strong></li>";
  $html .= "<li>Ordenado: <code>" . e(json_encode($orden)) . "</code></li>";
  $html .= "</ul>";
  return $html;
}

function renderEj6(?string $mat = null): string {
  $parque = parqueVehicular();

  // Si hay matrícula, muestra el detalle, sin cortar la ejecución del resto
  if ($mat) {
    $veh = buscarPorMatricula($parque, $mat);
    $html  = h2("Ejercicio 6 · Detalle del vehículo " . strtoupper(e($mat)));
    if ($veh) {
      $a = $veh['auto'];
      $p = $veh['propietario'];
      $html .= "<table>
        <tr><th>Matrícula</th><td><code>" . e(strtoupper($mat)) . "</code></td></tr>
        <tr><th>Marca</th>     <td>" . e($a['marca'])   . "</td></tr>
        <tr><th>Modelo</th>    <td>" . e((string)$a['modelo']) . "</td></tr>
        <tr><th>Tipo</th>      <td>" . e($a['tipo'])    . "</td></tr>
        <tr><th>Propietario</th><td>" . e($p['nombre']) . "</td></tr>
        <tr><th>Ciudad</th>    <td>" . e($p['ciudad'])  . "</td></tr>
        <tr><th>Dirección</th> <td>" . e($p['direccion']) . "</td></tr>
      </table>";
    } else {
      $html .= "<p>No existe la matrícula <code>" . e($mat) . "</code> en el parque.</p>";
    }
    $html .= '<p><a href="?ej=6">Volver al listado</a></p>';
    // Importante: NO hacemos return global que corte el resto de la página si estás en "todo"
  } else {
    $html  = h2("Ejercicio 6 · Parque vehicular");
    $html .= "<ul>";
    foreach ($parque as $matricula => $info) {
      $a = $info['auto'];
      $label = e($a['marca']) . ' ' . e((string)$a['modelo']) . ' (' . e($a['tipo']) . ')';
      $html .= "<li><a href='?ej=6&amp;mat=" . e($matricula) . "'>" . e($matricula) . "</a> — {$label}</li>";
    }
    $html .= "</ul>";
    $html .= "<p class='muted'>Total: " . e((string)count($parque)) . " vehículos</p>";
  }

  return $html;
}

function renderEj7(): string {
  // Inventario (arreglo de arreglos) + foreach anidado
  $inventario = [
    ['sku' => 'A-100', 'nombre' => 'Teclado',   'precio' => 299.90, 'stock' => 12],
    ['sku' => 'B-200', 'nombre' => 'Mouse',     'precio' => 199.00, 'stock' => 25],
    ['sku' => 'C-300', 'nombre' => 'Audífonos', 'precio' => 549.50, 'stock' =>  7],
  ];

  $totalArt = 0;
  $totalVal = 0.0;
  foreach ($inventario as $it) {
    $totalArt += $it['stock'];
    $totalVal += $it['precio'] * $it['stock'];
  }

  $html  = h2("Ejercicio 7 · Inventario con foreach");
  $html .= "<table><thead><tr><th>SKU</th><th>Artículo</th><th>Precio</th><th>Stock</th><th>Subtotal</th></tr></thead><tbody>";
  foreach ($inventario as $it) {
    $sub = $it['precio'] * $it['stock'];
    $html .= "<tr>
      <td><code>" . e($it['sku']) . "</code></td>
      <td>" . e($it['nombre']) . "</td>
      <td>$" . e(number_format($it['precio'], 2)) . "</td>
      <td style='text-align:right'>" . e((string)$it['stock']) . "</td>
      <td>$" . e(number_format($sub, 2)) . "</td>
    </tr>";
  }
  $html .= "</tbody></table>";
  $html .= "<p><strong>Total piezas:</strong> " . e((string)$totalArt) .
           " &nbsp; | &nbsp; <strong>Valor inventario:</strong> $" . e(number_format($totalVal, 2)) . "</p>";

  return $html;
}
