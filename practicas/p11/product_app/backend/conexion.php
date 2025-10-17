<?php
// product_app/backend/conexion.php

// Opcional: si quieres ver errores en el log pero NO mezclarlos con el JSON:
error_reporting(E_ALL);
ini_set('display_errors', 0);            // no imprimir en pantalla (rompe JSON)
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-errors.log');

function db(): mysqli {
    static $mysqli = null;
    if ($mysqli instanceof mysqli) {
        return $mysqli;
    }

    // Ajusta estos valores si tu puerto/user difieren
    $host = '127.0.0.1';
    $user = 'root';
    $pass = '';
    $db   = 'tienda';
    $port = 3306;

    $mysqli = @new mysqli($host, $user, $pass, $db, $port);
    if ($mysqli->connect_errno) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'ok'    => false,
            'error' => 'Error de conexión: ' . $mysqli->connect_error
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $mysqli->set_charset('utf8mb4');
    return $mysqli;
}
