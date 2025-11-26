<?php
namespace MYAPI\Create;

use MYAPI\DataBase;

class Create extends DataBase {

    public function __construct(string $db) {
        parent::__construct($db);
    }

    public function add(array $obj): void {
        $errors = [];

        $nombre   = trim($obj['nombre']   ?? '');
        $precio   = trim($obj['precio']   ?? '');
        $unidades = trim($obj['unidades'] ?? '');
        $modelo   = trim($obj['modelo']   ?? '');
        $marca    = trim($obj['marca']    ?? '');
        $detalles = trim($obj['detalles'] ?? '');

        if ($nombre === '' || mb_strlen($nombre) < 3) {
            $errors[] = 'El nombre es obligatorio y debe tener al menos 3 caracteres';
        }
        if ($precio === '' || !is_numeric($precio) || floatval($precio) <= 0) {
            $errors[] = 'El precio debe ser un numero mayor a 0';
        }
        if ($unidades === '' || !ctype_digit($unidades) || intval($unidades) < 0) {
            $errors[] = 'Las unidades deben ser un entero mayor o igual a 0';
        }
        if ($modelo === '') {
            $errors[] = 'El modelo es obligatorio';
        }
        if ($marca === '') {
            $errors[] = 'La marca es obligatoria';
        }

        // Nombre duplicado
        $nombreEsc = $this->conexion->real_escape_string($nombre);
        $sqlDup = "SELECT id FROM productos WHERE nombre = '$nombreEsc' AND eliminado = 0";
        $dupRes = $this->conexion->query($sqlDup);
        if ($dupRes && $dupRes->num_rows > 0) {
            $errors[] = 'Ya existe un producto con ese nombre';
        }

        if (!empty($errors)) {
            $this->data = [
                'status'  => 'error',
                'message' => 'No se pudo agregar el producto',
                'data'    => $errors
            ];
            return;
        }

        $modeloEsc   = $this->conexion->real_escape_string($modelo);
        $marcaEsc    = $this->conexion->real_escape_string($marca);
        $detallesEsc = $this->conexion->real_escape_string($detalles);
        $precioVal   = floatval($precio);
        $unidadesVal = intval($unidades);

        $sql = "INSERT INTO productos (nombre, modelo, marca, precio, unidades, detalles, imagen, eliminado)
                VALUES ('$nombreEsc', '$modeloEsc', '$marcaEsc', $precioVal, $unidadesVal, '$detallesEsc', 'img/default.png', 0)";

        if ($this->conexion->query($sql)) {
            $this->data = [
                'status'  => 'success',
                'message' => 'Producto agregado correctamente',
                'data'    => ['id' => $this->conexion->insert_id]
            ];
        } else {
            $this->data['message'] = 'Error al agregar: ' . $this->conexion->error;
        }
    }
}
