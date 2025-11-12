<?php
namespace MYAPI;

/**
 * Clase abstracta DataBase
 * Maneja la conexión a la base de datos MySQL
 */
abstract class DataBase {
    /**
     * Objeto de conexión a la base de datos
     * @var \mysqli
     */
    protected $conexion;

    /**
     * Constructor de la clase
     * Inicializa la conexión a la base de datos
     * 
     * @param string $db Nombre de la base de datos
     * @param string $user Usuario de la base de datos (por defecto: 'root')
     * @param string $pass Contraseña de la base de datos (por defecto: '')
     * @param string $host Servidor de la base de datos (por defecto: 'localhost')
     */
    protected function __construct($db, $user = 'root', $pass = '', $host = 'localhost') {
        // Suprimir errores de conexión con @
        $this->conexion = @new \mysqli($host, $user, $pass, $db);
        
        // Verificar si la conexión fue exitosa
        if ($this->conexion->connect_error) {
            die(json_encode([
                'status' => 'error',
                'message' => 'Error de conexión a la base de datos: ' . $this->conexion->connect_error
            ]));
        }
        
        // Establecer charset UTF-8
        $this->conexion->set_charset("utf8");
    }

    /**
     * Destructor de la clase
     * Cierra la conexión a la base de datos
     */
    public function __destruct() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
?>