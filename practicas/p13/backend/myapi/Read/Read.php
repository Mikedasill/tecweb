<?php
namespace MYAPI\Read;

use MYAPI\DataBase;

class Read extends DataBase {

    public function __construct(string $db) {
        parent::__construct($db);
    }

    /** LISTA INICIAL: debe regresar un ARRAY de productos */
    public function list(): void {
        // Si hubo error de conexión, $this->data ya trae el objeto de error
        if ($this->conexion->connect_error) {
            return;
        }

        $sql = "SELECT * FROM productos WHERE eliminado = 0";
        $res = $this->conexion->query($sql);

        if ($res) {
            // <- AQUÍ: solo el array, como esperaba app.js
            $this->data = $res->fetch_all(MYSQLI_ASSOC);
        } else {
            $this->data = [];
        }
    }

    /** BÚSQUEDA: también debe regresar ARRAY para que app.js pinte la tabla */
    public function search(string $term): void {
        if ($this->conexion->connect_error) {
            return;
        }

        $termEsc = $this->conexion->real_escape_string($term);
        $idCond  = is_numeric($term) ? "OR id = " . intval($term) : "";

        $sql = "SELECT * FROM productos
                WHERE eliminado = 0 AND (
                    nombre LIKE '%$termEsc%' OR
                    marca  LIKE '%$termEsc%' OR
                    modelo LIKE '%$termEsc%' OR
                    detalles LIKE '%$termEsc%'
                    $idCond
                )";

        $res = $this->conexion->query($sql);

        if ($res) {
            $this->data = $res->fetch_all(MYSQLI_ASSOC);
        } else {
            $this->data = [];
        }
    }

    /** UN SOLO PRODUCTO PARA EDITAR (devuelve un objeto o null) */
    public function single(string $id): void {
        if ($this->conexion->connect_error) {
            return;
        }

        $idVal = intval($id);
        $sql = "SELECT * FROM productos WHERE id = $idVal AND eliminado = 0";
        $res = $this->conexion->query($sql);

        if ($res && $row = $res->fetch_assoc()) {
            $this->data = $row;   // objeto
        } else {
            $this->data = null;
        }
    }

    /** PARA test-add.php: sí usamos status/mensaje */
    public function singleByName(string $nombre): void {
        if ($this->conexion->connect_error) {
            return;
        }

        $nombreEsc = $this->conexion->real_escape_string($nombre);
        $sql = "SELECT * FROM productos WHERE nombre = '$nombreEsc' AND eliminado = 0";
        $res = $this->conexion->query($sql);

        if ($res && $row = $res->fetch_assoc()) {
            $this->data = [
                'status'  => 'success',
                'message' => 'Nombre ocupado',
                'data'    => $row
            ];
        } else {
            $this->data = [
                'status'  => 'success',
                'message' => 'Nombre disponible',
                'data'    => null
            ];
        }
    }
}

