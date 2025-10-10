<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "tienda";
$port = 3306;

$mysqli = new mysqli($host, $user, $pass, $db, $port);
if ($mysqli->connect_errno) {
  die("Error de conexión: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
?>
