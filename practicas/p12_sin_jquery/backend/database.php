<?php
$conexion = @mysqli_connect(
  'localhost',   // host
  'root',        // usuario por defecto en XAMPP
  '',            // password (vacío en XAMPP por default)
  'marketzone'   // nombre de la BD que acabas de crear
);

if (!$conexion) {
  die('¡Base de datos NO conectada!');
}