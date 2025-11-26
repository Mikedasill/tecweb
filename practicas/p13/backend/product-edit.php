<?php
// Limpiar buffer y establecer headers
/*ob_start();
header('Content-Type: application/json; charset=utf-8');
ob_end_clean();

use MYAPI\Products;
require_once __DIR__ . '/myapi/Products.php';

$products = new Products('marketzone');
$products->edit($_POST);
echo $products->getData();
?>*/
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';

use MYAPI\Update\Update;

$api = new Update('marketzone');
$api->edit($_POST);

echo $api->getData();
