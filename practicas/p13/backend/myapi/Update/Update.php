<?php
namespace MYAPI\Update;

use MYAPI\DataBase;

class Update extends DataBase {

    public function __construct(string $db) {
        parent::__construct($db);
    }

    public function edit(array $obj): void {
        $errors = [];

        $id       = intval($obj['id']       ?? 0);
        $nombre   = trim($obj['nombre']   ?? '');
        $precio   = trim($obj['precio']   ?? '');
        $unidades = trim($obj['unidades'] ?? '');
        $modelo   = trim($obj['modelo']   ?? '');
        $marca    = trim($obj['marca']    ?? '');
        $detalles = trim($obj['detalles'] ?? '');

        if ($id <= 0) {
            $errors[] = 'ID invalido';
        }
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

        if (!empty($errors)) {
            $this->data = [
                'status'  => 'error',
                'message' => 'No se pudo actualizar el producto',
                'data'    => $errors
            ];
            return;
        }

        $nombreEsc   = $this->conexion->real_escape_string($nombre);
        $modeloEsc   = $this->conexion->real_escape_string($modelo);
        $marcaEsc    = $this->conexion->real_escape_string($marca);
        $detallesEsc = $this->conexion->real_escape_string($detalles);
        $precioVal   = floatval($precio);
        $unidadesVal = intval($unidades);

        $sql = "UPDATE productos
                SET nombre   = '$nombreEsc',
                    modelo   = '$modeloEsc',
                    marca    = '$marcaEsc',
                    precio   = $precioVal,
                    unidades = $unidadesVal,
                    detalles = '$detallesEsc'
                WHERE id = $id";

        if ($this->conexion->query($sql)) {
            $this->data = [
                'status'  => 'success',
                'message' => 'Producto actualizado correctamente',
                'data'    => null
            ];
        } else {
            $this->data['message'] = 'Error al actualizar: ' . $this->conexion->error;
        }
    }
}

