<?php
// practicas/p07/index.php
require_once __DIR__ . '/src/src.php';

$parque = parqueVehicular();

$mat = $_GET['mat'] ?? null;
if ($mat) {
  $registro = buscarPorMatricula($parque, $mat);
  if ($registro) {
    renderDetalles($mat, $registro);
  } else {
    echo "<p>No se encontró la matrícula <strong>" . htmlspecialchars($mat) . "</strong>.</p>";
    echo "<p><a href='index.php'>Volver al listado</a></p>";
  }
} else {
  renderLista($parque);
}



