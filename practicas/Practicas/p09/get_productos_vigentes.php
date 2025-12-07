<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<?php
	/** SE CREA EL OBJETO DE CONEXION */
	@$link = new mysqli('localhost', 'root', 'Ubuntu12', 'marketzone');

	/** comprobar la conexión */
	if ($link->connect_errno) 
	{
		die('Falló la conexión: '.$link->connect_error.'<br/>');
	}

	/** Obtener solo productos NO eliminados */
	if ( $result = $link->query("SELECT * FROM productos WHERE eliminado = 0") ) 
	{
		$row = $result->fetch_all(MYSQLI_ASSOC);
		$result->free();
	}

	$link->close();
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Productos Vigentes</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<h3>PRODUCTOS VIGENTES</h3>
	<br/>
	
	<?php if( isset($row) && !empty($row) ) : ?>
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Nombre</th>
					<th scope="col">Marca</th>
					<th scope="col">Modelo</th>
					<th scope="col">Precio</th>
					<th scope="col">Unidades</th>
					<th scope="col">Detalles</th>
					<th scope="col">Imagen</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($row as $value) : ?>
				<tr>
					<th scope="row"><?= htmlspecialchars($value['id']) ?></th>
					<td><?= htmlspecialchars($value['nombre']) ?></td>
					<td><?= htmlspecialchars($value['marca']) ?></td>
					<td><?= htmlspecialchars($value['modelo']) ?></td>
					<td><?= htmlspecialchars($value['precio']) ?></td>
					<td><?= htmlspecialchars($value['unidades']) ?></td>
					<td><?= htmlspecialchars($value['detalles']) ?></td>
					<td><img src="<?= htmlspecialchars($value['imagen']) ?>" width="50"></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else : ?>
		<p>No hay productos vigentes.</p>
	<?php endif; ?>
</body>
</html>