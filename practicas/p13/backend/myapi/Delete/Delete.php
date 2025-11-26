<?php
namespace MYAPI\Delete;

use MYAPI\DataBase;

class Delete extends DataBase {

    public function __construct(string $db) {
        parent::__construct($db);
    }

    public function delete(string $id): void {
        $idVal = intval($id);
        if ($idVal <= 0) {
            $this->data['message'] = 'ID invalido';
            return;
        }

        $sql = "UPDATE productos SET eliminado = 1 WHERE id = $idVal";

        if ($this->conexion->query($sql)) {
            $this->data = [
                'status'  => 'success',
                'message' => 'Producto eliminado correctamente',
                'data'    => null
            ];
        } else {
            $this->data['message'] = 'Error al eliminar: ' . $this->conexion->error;
        }
    }
}
