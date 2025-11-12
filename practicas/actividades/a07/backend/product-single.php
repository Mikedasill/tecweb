<?php
// Limpiar buffer y establecer headers
ob_start();
header('Content-Type: application/json; charset=utf-8');
ob_end_clean();

use MYAPI\Products;
require_once __DIR__ . '/myapi/Products.php';

$id = isset($_POST['id']) ? $_POST['id'] : '';
$products = new Products('marketzone');
$products->single($id);
echo $products->getData();
?>