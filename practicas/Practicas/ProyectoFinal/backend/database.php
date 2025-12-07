<?php
    // Iniciamos sesión para poder usar $_SESSION en todo el proyecto
    session_start();

    $conexion = @mysqli_connect(
        'localhost',   // host
        'root',        // usuario por defecto en XAMPP
        '',            // contraseña VACÍA (como ya usabas en marketzone)
        'dashboard_recursos'   // nombre de la BD para el proyecto final
    );

    if (!$conexion) {
        die('Error de conexión: ' . mysqli_connect_error());
    }

    // Forzar UTF-8
    $conexion->set_charset("utf8");
?>
