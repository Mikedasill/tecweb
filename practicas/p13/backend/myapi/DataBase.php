<?php 
namespace MYAPI;

abstract class DataBase {
    protected $conexion;

    // Puede ser arreglo de filas o un objeto con status/mensaje
    protected $data = [];

    protected function __construct($db, $user = 'root', $pass = '', $host = 'localhost') {
        $this->conexion = @new \mysqli($host, $user, $pass, $db);

        if ($this->conexion->connect_error) {
            // Si falla la conexión, mandamos un objeto de error
            $this->data = [
                'status'  => 'error',
                'message' => 'Error de conexion a la base de datos: ' . $this->conexion->connect_error,
                'data'    => null
            ];
        } else {
            $this->conexion->set_charset("utf8");
        }
    }

    public function getData(): string {
        return json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }

    public function __destruct() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
