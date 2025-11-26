<?php
// Limpiar buffer y establecer headers
/*ob_start();
header('Content-Type: application/json; charset=utf-8');
ob_end_clean();

use MYAPI\Products;
require_once __DIR__ . '/myapi/Products.php';

$products = new Products('marketzone');
$products->list();
echo $products->getData();
?>*/
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';

use MYAPI\Read\Read;

$api = new Read('marketzone');
$api->list();

echo $api->getData();
