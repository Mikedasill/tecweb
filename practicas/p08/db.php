<?php
/* practicas/p8/db.php */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db(): mysqli {
  $host = 'localhost';
  $user = 'root';
  $pass = '';          // tu pass de phpMyAdmin si la configuraste
  $db   = 'marketzone';

  $cn = new mysqli($host, $user, $pass, $db);
  $cn->set_charset('utf8mb4'); // asegura UTF-8
  return $cn;
}
