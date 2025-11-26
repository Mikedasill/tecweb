<?php
// Limpiar buffer y establecer headers
ob_start();
/*header('Content-Type: application/json; charset=utf-8');
ob_end_clean();

use MYAPI\Products;
require_once __DIR__ . '/myapi/Products.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$products = new Products('marketzone');
$products->search($search);
echo $products->getData();
?>*/

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';

use MYAPI\Read\Read;

$search = $_GET['search'] ?? '';

$api = new Read('marketzone');
$api->search($search);

echo $api->getData();
