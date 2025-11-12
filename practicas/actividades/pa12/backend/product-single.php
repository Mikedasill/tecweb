<?php
include 'database.php';

$id = $_POST['id'];

$query = "SELECT * FROM productos WHERE id='$id' AND eliminado = 0"; // Solo los productos no eliminados
$result = $conn->query($query);

$product = $result->fetch_assoc();

echo json_encode($product);

$conn->close();
?>
