<?php
// Por si el php.ini aún no se recarga, forzamos la zona horaria
date_default_timezone_set('America/Mexico_City');

// Muestra la fecha/hora en formato ISO (ideal para la evidencia en terminal)
echo date(DATE_ATOM), PHP_EOL;