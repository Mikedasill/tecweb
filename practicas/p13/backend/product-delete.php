<?php
// Limpiar buffer y establecer headers
/*ob_start();
header('Content-Type: application/json; charset=utf-8');
ob_end_clean();

use MYAPI\Products;
require_once __DIR__ . '/myapi/Products.php';

$id = isset($_POST['id']) ? $_POST['id'] : '';
$products = new Products('marketzone');
$products->delete($id);
echo $products->getData();
?>*/
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';

use MYAPI\Delete\Delete;

$id = $_POST['id'] ?? '';

$api = new Delete('marketzone');
$api->delete($id);

echo $api->getData();
