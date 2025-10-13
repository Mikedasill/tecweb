<?php
// practicas/p10/conexion.php
declare(strict_types=1);

// Muestra errores mientras depuramos:
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$DB_HOST = '127.0.0.1';   // usa 127.0.0.1 para forzar TCP
$DB_USER = 'root';
$DB_PASS = '';            // XAMPP por defecto
$DB_NAME = 'tienda';      // asegúrate que exista (la creaste en p09)
$DB_PORT = 3306;          // según tu XAMPP (en el panel se ve 3306)

try {
    $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);
    $mysqli->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Mensaje claro si falla la conexión
    die('Error de conexión MySQL: ' . $e->getMessage());
}
