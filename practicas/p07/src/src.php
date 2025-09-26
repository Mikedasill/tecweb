<?php
// practicas/p07/src/src.php
declare(strict_types=1);

// Zona horaria segura
date_default_timezone_set('America/Mexico_City');

/* =======================
 * Helpers
 * =====================*/
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function h2(string $t): string { return "<h2>".e($t)."</h2>"; }

/* =========================================================
 * EJERCICIO 1
 * ¿Múltiplo de 5 y 7? (GET ?numero=...)
 * =======================================================*/
function renderEj1(): string {
  $html  = h2("Ejercicio 1 · ¿Múltiplo de 5 y 7? (GET)");
  $numero = isset($_GET['numero']) ? (int)$_GET['numero'] : null;

  $html .= '<form method="get">
              <input type="hidden" name="ej" value="1" />
              <label>Número: <input type="number" name="numero" required></label>
              <button type="submit">Comprobar</button>
              <span class="muted">o usa: <code>?ej=1&amp;numero=35</code></span>
            </form>';

  if ($numero === null) {
    return $html.'<p class="muted">Ingresa un valor o pásalo en la URL.</p>';
  }

  $esMultiplo = ($numero % 5 === 0) && ($numero % 7 === 0);
  $html .= "<p>Número: <strong>".e((string)$numero)."</strong></p>";
  $html .= $esMultiplo
    ? '<p class="ok">✅ Sí, es múltiplo de 5 y 7.</p>'
    : '<p class="bad">❌ No es múltiplo de 5 y 7.</p>';

  return $html;
}

/* =========================================================
 * EJERCICIO 2
 * Triples hasta impar, par, impar (matriz M×3)
 * =======================================================*/
function generarTriple(): array {
  return [random_int(1, 999), random_int(1, 999), random_int(1, 999)];
}
function esImparParImpar(array $t): bool {
  [$a,$b,$c] = $t;
  return ($a % 2 !== 0) && ($b % 2 === 0) && ($c % 2 !== 0);
}
function renderEj2(): string {
  $html = h2("Ejercicio 2 · Triples hasta lograr impar, par, impar");

  $matriz = [];
  do {
    $t = generarTriple();
    $matriz[] = $t;
  } while (!esImparParImpar($t));

  $iter = count($matriz);
  $totalNums = $iter * 3;

  $html .= "<table><thead><tr><th>#</th><th>a</th><th>b</th><th>c</th><th>patrón</th></tr></thead><tbody>";
  foreach ($matriz as $i => $triple) {
    $ok = esImparParImpar($triple);
    $html .= '<tr'.($ok?' class="row-ok"':'').'>';
    $html .= '<td>'.e((string)($i+1)).'</td>';
    $html .= '<td>'.e((string)$triple[0]).'</td>';
    $html .= '<td>'.e((string)$triple[1]).'</td>';
    $html .= '<td>'.e((string)$triple[2]).'</td>';
    $html .= '<td>'.($ok?'<span class="chip ok">impar, par, impar</span>':'—').'</td>';
    $html .= '</tr>';
  }
  $html .= "</tbody></table>";

  $html .= "<p><strong>{$totalNums}</strong> números obtenidos en <strong>{$iter}</strong> iteraciones.</p>";
  return $html;
}

/* =========================================================
 * EJERCICIO 3
 * while / do-while: primer aleatorio múltiplo de n (GET ?n=...)
 * =======================================================*/
function buscarMultiploConWhile(int $n, int $min=1, int $max=10000): array {
  $intentos = 0; $num = null;
  while (true) {
    $intentos++;
    $num = random_int($min, $max);
    if ($num % $n === 0) break;
  }
  return ['valor'=>$num, 'intentos'=>$intentos];
}
function buscarMultiploConDoWhile(int $n, int $min=1, int $max=10000): array {
  $intentos = 0; $num = null;
  do {
    $intentos++;
    $num = random_int($min, $max);
  } while ($num % $n !== 0);
  return ['valor'=>$num, 'intentos'=>$intentos];
}
function renderEj3(): string {
  $html = h2("Ejercicio 3 · Primer múltiplo aleatorio de n (while / do-while)");

  $n = isset($_GET['n']) ? (int)$_GET['n'] : 7;
  $html .= '<form method="get">
              <input type="hidden" name="ej" value="3" />
              <label>n (divisor): <input type="number" name="n" min="1" value="'.e((string)$n).'" required></label>
              <button type="submit">Probar</button>
              <span class="muted">Ej.: <code>?ej=3&amp;n=13</code></span>
            </form>';

  if ($n < 1) {
    return $html.'<p class="bad">n debe ser un entero &ge; 1.</p>';
  }

  $r1 = buscarMultiploConWhile($n);
  $r2 = buscarMultiploConDoWhile($n);

  $html .= "<table><thead><tr><th>Método</th><th>Resultado</th><th>Intentos</th></tr></thead><tbody>";
  $html .= "<tr><td>while</td><td><strong>".e((string)$r1['valor'])."</strong></td><td>".e((string)$r1['intentos'])."</td></tr>";
  $html .= "<tr><td>do-while</td><td><strong>".e((string)$r2['valor'])."</strong></td><td>".e((string)$r2['intentos'])."</td></tr>";
  $html .= "</tbody></table>";
  return $html;
}

/* =========================================================
 * EJERCICIO 4
 * Arreglo índices 97..122 → 'a'..'z' (for + foreach)
 * =======================================================*/
function arregloAsciiAZ(): array {
  $arr = [];
  for ($i = 97; $i <= 122; $i++) {
    $arr[$i] = chr($i);
  }
  return $arr;
}
function renderEj4(): string {
  $html = h2("Ejercicio 4 · ASCII 97..122 → a..z (for + foreach)");
  $arr = arregloAsciiAZ();
  $html .= "<table><thead><tr><th>Índice</th><th>Valor</th></tr></thead><tbody>";
  foreach ($arr as $key => $val) {
    $html .= "<tr><td>".e((string)$key)."</td><td>".e($val)."</td></tr>";
  }
  $html .= "</tbody></table>";
  return $html;
}

/* =========================================================
 * EJERCICIO 5
 * POST: sexo/edad → bienvenida si femenino y 18–35
 * =======================================================*/
function evaluarBienvenida(?string $sexo, ?int $edad): array {
  if ($sexo === null || $edad === null) {
    return ['ok'=>false, 'msg'=>'Completa el formulario.'];
  }
  $sexoNorm = strtolower(trim($sexo));
  $ok = ($sexoNorm === 'f' || $sexoNorm === 'femenino')
        && $edad >= 18 && $edad <= 35;
  return [
    'ok'  => $ok,
    'msg' => $ok
      ? 'Bienvenida, usted está en el rango de edad permitido.'
      : 'Lo sentimos, no cumple el criterio (sexo femenino y edad entre 18 y 35 años).'
  ];
}
function renderEj5(): string {
  $html = h2("Ejercicio 5 · Validación por POST (sexo/edad)");

  $html .= '<form method="post" style="display:grid; gap:.5rem; max-width:420px">
    <input type="hidden" name="ej" value="5" />
    <label>Sexo:
      <select name="sexo" required>
        <option value="">-- Selecciona --</option>
        <option value="f">Femenino</option>
        <option value="m">Masculino</option>
      </select>
    </label>
    <label>Edad:
      <input type="number" name="edad" min="0" max="120" required>
    </label>
    <button type="submit">Enviar</button>
    <p class="muted">Se evalúa: sexo = femenino y 18–35 años.</p>
  </form>';

  // Para evitar colisiones cuando se muestra "todo", revisamos el hidden ej=5
  if (($_SERVER['REQUEST_METHOD'] === 'POST') && (($_POST['ej'] ?? '') === '5')) {
    $sexo = isset($_POST['sexo']) ? (string)$_POST['sexo'] : null;
    $edad = isset($_POST['edad']) ? (int)$_POST['edad'] : null;

    $r = evaluarBienvenida($sexo, $edad);
    $html .= '<p><strong>Datos:</strong> sexo=<code>'.e((string)$sexo).'</code>, edad=<code>'.e((string)$edad).'</code></p>';
    $html .= $r['ok']
      ? '<p class="ok">✅ '.e($r['msg']).'</p>'
      : '<p class="bad">❌ '.e($r['msg']).'</p>';
  }

  return $html;
}

/* =========================================================
 * EJERCICIO 6
 * Parque vehicular: 15 registros en código duro (matrícula LLLNNNN)
 * - Clave = matrícula (única)
 * - 'Auto' => marca, modelo (año), tipo (sedan|hachback|camioneta)
 * - 'Propietario' => nombre, ciudad, direccion
 * - Formulario: buscar por matrícula / mostrar todos (print_r)
 * =======================================================*/
function parqueVehicular(): array {
  return [
    'UBN6338' => [
      'Auto' => ['marca'=>'HONDA',     'modelo'=>2020, 'tipo'=>'camioneta'],
      'Propietario' => ['nombre'=>'Alfonzo Esparza', 'ciudad'=>'Puebla, Pue.', 'direccion'=>'C.U., Jardines de San Manuel'],
    ],
    'UBN6339' => [
      'Auto' => ['marca'=>'MAZDA',     'modelo'=>2019, 'tipo'=>'sedan'],
      'Propietario' => ['nombre'=>'Ma. del Consuelo Molina', 'ciudad'=>'Puebla, Pue.', 'direccion'=>'97 oriente'],
    ],
    'ABC1234' => [
      'Auto' => ['marca'=>'NISSAN',    'modelo'=>2018, 'tipo'=>'hachback'],
      'Propietario' => ['nombre'=>'María Ruiz', 'ciudad'=>'Guadalajara, Jal.', 'direccion'=>'Centro'],
    ],
    'BCD2345' => [
      'Auto' => ['marca'=>'VW',        'modelo'=>2021, 'tipo'=>'sedan'],
      'Propietario' => ['nombre'=>'Jorge Díaz', 'ciudad'=>'Monterrey, NL', 'direccion'=>'Obispado'],
    ],
    'CDE3456' => [
      'Auto' => ['marca'=>'KIA',       'modelo'=>2022, 'tipo'=>'camioneta'],
      'Propietario' => ['nombre'=>'Sara Neri', 'ciudad'=>'Puebla, Pue.', 'direccion'=>'Angelópolis'],
    ],
    'DEF4567' => [
      'Auto' => ['marca'=>'TOYOTA',    'modelo'=>2017, 'tipo'=>'sedan'],
      'Propietario' => ['nombre'=>'Miguel Ochoa', 'ciudad'=>'León, Gto.', 'direccion'=>'Centro'],
    ],
    'EFG5678' => [
      'Auto' => ['marca'=>'FORD',      'modelo'=>2015, 'tipo'=>'camioneta'],
      'Propietario' => ['nombre'=>'Julia Ríos', 'ciudad'=>'Querétaro, Qro.', 'direccion'=>'Juriquilla'],
    ],
    'FGH6789' => [
      'Auto' => ['marca'=>'CHEVROLET', 'modelo'=>2020, 'tipo'=>'hachback'],
      'Propietario' => ['nombre'=>'Iván Mora', 'ciudad'=>'Puebla, Pue.', 'direccion'=>'Las Ánimas'],
    ],
    'GHI7890' => [
      'Auto' => ['marca'=>'SEAT',      'modelo'=>2019, 'tipo'=>'sedan'],
      'Propietario' => ['nombre'=>'Olga Vera', 'ciudad'=>'Toluca, Méx.', 'direccion'=>'Centro'],
    ],
    'HIJ8901' => [
      'Auto' => ['marca'=>'BMW',       'modelo'=>2018, 'tipo'=>'sedan'],
      'Propietario' => ['nombre'=>'Héctor Gil', 'ciudad'=>'CDMX', 'direccion'=>'Del Valle'],
    ],
    'IJK9012' => [
      'Auto' => ['marca'=>'AUDI',      'modelo'=>2017, 'tipo'=>'sedan'],
      'Propietario' => ['nombre'=>'Cecilia Mar', 'ciudad'=>'CDMX', 'direccion'=>'Polanco'],
    ],
    'JKL0123' => [
      'Auto' => ['marca'=>'RENAULT',   'modelo'=>2016, 'tipo'=>'hachback'],
      'Propietario' => ['nombre'=>'Bruno Sol', 'ciudad'=>'Puebla, Pue.', 'direccion'=>'Zavaleta'],
    ],
    'KLM1235' => [
      'Auto' => ['marca'=>'PEUGEOT',   'modelo'=>2015, 'tipo'=>'sedan'],
      'Propietario' => ['nombre'=>'Tania Rey', 'ciudad'=>'Querétaro, Qro.', 'direccion'=>'Tecnológico'],
    ],
    'LMN2346' => [
      'Auto' => ['marca'=>'DODGE',     'modelo'=>2014, 'tipo'=>'sedan'],
      'Propietario' => ['nombre'=>'Oscar Paz', 'ciudad'=>'Monterrey, NL', 'direccion'=>'San Pedro'],
    ],
    'MNO3457' => [
      'Auto' => ['marca'=>'HYUNDAI',   'modelo'=>2016, 'tipo'=>'camioneta'],
      'Propietario' => ['nombre'=>'Lola Cruz', 'ciudad'=>'Guadalajara, Jal.', 'direccion'=>'Providencia'],
    ],
  ];
}
function buscarPorMatricula(array $parque, string $matricula): ?array {
  $matricula = strtoupper(trim($matricula));
  return $parque[$matricula] ?? null;
}
function renderEj6(): string {
  $html = h2("Ejercicio 6 · Parque vehicular (15 autos, clave = matrícula)");

  // Formulario (POST) con dos acciones
  $html .= '<form method="post" style="display:flex; gap:.5rem; flex-wrap:wrap; align-items:center">
    <input type="hidden" name="ej" value="6" />
    <label>Matrícula:
      <input name="placa" pattern="[A-Za-z]{3}[0-9]{4}" title="Tres letras y cuatro dígitos. Ej. ABC1234" />
    </label>
    <button type="submit" name="buscar" value="1">Buscar por matrícula</button>
    <button type="submit" name="todos" value="1">Mostrar todos</button>
  </form>';

  $parque = parqueVehicular();

  // Procesamiento (solo si viene de este ejercicio)
  if (($_SERVER['REQUEST_METHOD'] === 'POST') && (($_POST['ej'] ?? '') === '6')) {

    if (!empty($_POST['todos'])) {
      // Estructura general (print_r como pide la guía)
      $html .= '<p class="muted">Mostrando la estructura completa con <code>print_r</code>:</p>';
      $html .= '<pre class="mono">'.e(print_r($parque, true)).'</pre>';
      $html .= '<p>Total de autos: <strong>'.count($parque).'</strong></p>';
      return $html;
    }

    if (!empty($_POST['buscar'])) {
      $placa = $_POST['placa'] ?? '';
      $dato = buscarPorMatricula($parque, $placa);

      if ($dato) {
        // Mostrar la misma estructura (matrícula => registro) con print_r
        $html .= '<p class="muted">Resultado de la búsqueda con <code>print_r</code>:</p>';
        $html .= '<pre class="mono">'.e(print_r([strtoupper($placa) => $dato], true)).'</pre>';
      } else {
        $html .= '<p class="bad">No se encontró la matrícula <code>'.e($placa).'</code>.</p>';
      }
    }
  }

  // Además del print_r, deja una vista compacta con links (útil para navegar)
  $html .= "<h3>Vista compacta</h3><ul>";
  foreach ($parque as $mat => $info) {
    $a = $info['Auto'];
    $html .= "<li><strong>".e($mat)."</strong> — ".e($a['marca'])." ".e((string)$a['modelo'])." (".e($a['tipo']).")</li>";
  }
  $html .= "</ul>";
  $html .= '<p class="muted">Tip: puedes escribir una matrícula válida y “Buscar por matrícula”, o “Mostrar todos”.</p>';

  return $html;
}

