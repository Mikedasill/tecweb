<?php
namespace MYAPI;

require_once __DIR__ . '/DataBase.php';

/**
 * Clase Products
 * Maneja las operaciones CRUD de productos
 */
class Products extends DataBase {
    /**
     * Array que almacena la respuesta de las operaciones
     * @var array
     */
    private $response;

    /**
     * Constructor de la clase
     * 
     * @param string $db Nombre de la base de datos
     * @param string $user Usuario de la base de datos (por defecto: 'root')
     * @param string $pass Contraseña de la base de datos (por defecto: '12345678a')
     */
 
 
    public function __construct($db, $user = 'root', $pass = '') {
    // Inicializar response
    $this->response = array();
    
    // Llamar al constructor de la clase padre (DataBase)
    parent::__construct($db, $user, $pass);
}

    /**
     * Obtiene todos los productos no eliminados
     * @return void
     */
    public function list() {
        $sql = "SELECT * FROM productos WHERE eliminado = 0 ORDER BY id DESC";
        
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            
            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->response[$num][$key] = is_string($value) ? utf8_encode($value) : $value;
                    }
                }
            }
            $result->free();
        } else {
            $this->response = [
                'status' => 'error',
                'message' => 'Error en la consulta: ' . $this->conexion->error
            ];
        }
    }

    /**
     * Busca productos por término de búsqueda
     * 
     * @param string $search Término de búsqueda
     * @return void
     */
    public function search($search) {
        if (empty($search)) {
            $this->response = [];
            return;
        }
        
        $searchEscaped = $this->conexion->real_escape_string($search);
        
        $sql = "SELECT * FROM productos 
                WHERE (
                    id = '{$searchEscaped}' OR 
                    nombre LIKE '%{$searchEscaped}%' OR 
                    marca LIKE '%{$searchEscaped}%' OR 
                    detalles LIKE '%{$searchEscaped}%'
                ) 
                AND eliminado = 0 
                ORDER BY nombre ASC
                LIMIT 50";
        
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            
            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->response[$num][$key] = is_string($value) ? utf8_encode($value) : $value;
                    }
                }
            }
            $result->free();
        } else {
            $this->response = [
                'status' => 'error',
                'message' => 'Error en la búsqueda: ' . $this->conexion->error
            ];
        }
    }

    /**
     * Obtiene un producto por su ID
     * 
     * @param int $id ID del producto
     * @return void
     */
    public function single($id) {
        if (empty($id)) {
            $this->response = [
                'status' => 'error',
                'message' => 'ID no proporcionado'
            ];
            return;
        }
        
        $idInt = intval($id);
        $sql = "SELECT * FROM productos WHERE id = {$idInt}";
        
        if ($result = $this->conexion->query($sql)) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                foreach ($row as $key => $value) {
                    $this->response[$key] = is_string($value) ? utf8_encode($value) : $value;
                }
            } else {
                $this->response = [
                    'status' => 'error',
                    'message' => 'Producto no encontrado'
                ];
            }
            $result->free();
        } else {
            $this->response = [
                'status' => 'error',
                'message' => 'Error en la consulta: ' . $this->conexion->error
            ];
        }
    }

    /**
     * Obtiene un producto por su nombre
     * 
     * @param string $name Nombre del producto
     * @return void
     */
    public function singleByName($name) {
        if (empty($name)) {
            $this->response = [
                'status' => 'error',
                'message' => 'Nombre no proporcionado'
            ];
            return;
        }
        
        $nameEscaped = $this->conexion->real_escape_string($name);
        $sql = "SELECT * FROM productos WHERE nombre = '{$nameEscaped}' AND eliminado = 0 LIMIT 1";
        
        if ($result = $this->conexion->query($sql)) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                foreach ($row as $key => $value) {
                    $this->response[$key] = is_string($value) ? utf8_encode($value) : $value;
                }
            } else {
                $this->response = [];
            }
            $result->free();
        } else {
            $this->response = [
                'status' => 'error',
                'message' => 'Error en la consulta: ' . $this->conexion->error
            ];
        }
    }

    /**
     * Agrega un nuevo producto
     * 
     * @param array $data Datos del producto
     * @return void
     */
    public function add($data) {
        // Validar datos requeridos
        if (empty($data['nombre']) || empty($data['marca']) || empty($data['modelo']) ||
            empty($data['precio']) || !isset($data['unidades'])) {
            $this->response = [
                'status' => 'error',
                'message' => 'Datos incompletos'
            ];
            return;
        }
        
        // Verificar que el nombre no exista
        $nombreEscaped = $this->conexion->real_escape_string(trim($data['nombre']));
        $sqlCheck = "SELECT id FROM productos WHERE nombre = '{$nombreEscaped}' AND eliminado = 0";
        $resultCheck = $this->conexion->query($sqlCheck);
        
        if ($resultCheck && $resultCheck->num_rows > 0) {
            $this->response = [
                'status' => 'error',
                'message' => 'Ya existe un producto con ese nombre'
            ];
            $resultCheck->free();
            return;
        }
        
        // Escapar y preparar datos
        $nombre = $this->conexion->real_escape_string(trim($data['nombre']));
        $marca = $this->conexion->real_escape_string(trim($data['marca']));
        $modelo = $this->conexion->real_escape_string(trim($data['modelo']));
        $precio = floatval($data['precio']);
        $unidades = intval($data['unidades']);
        $detalles = isset($data['detalles']) ? $this->conexion->real_escape_string(trim($data['detalles'])) : 'NA';
        $imagen = isset($data['imagen']) && !empty($data['imagen']) ? $this->conexion->real_escape_string(trim($data['imagen'])) : 'img/default.png';
        
        // Insertar producto
        $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                VALUES ('{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}', 0)";
        
        if ($this->conexion->query($sql)) {
            $this->response = [
                'status' => 'success',
                'message' => 'Producto agregado correctamente',
                'id' => $this->conexion->insert_id
            ];
        } else {
            $this->response = [
                'status' => 'error',
                'message' => 'Error al agregar el producto: ' . $this->conexion->error
            ];
        }
    }

    /**
     * Elimina un producto (borrado lógico)
     * 
     * @param int $id ID del producto a eliminar
     * @return void
     */
    public function delete($id) {
        if (empty($id)) {
            $this->response = [
                'status' => 'error',
                'message' => 'ID no proporcionado'
            ];
            return;
        }
        
        $idInt = intval($id);
        
        // Verificar que el producto existe
        $sqlCheck = "SELECT nombre FROM productos WHERE id = {$idInt} AND eliminado = 0";
        $resultCheck = $this->conexion->query($sqlCheck);
        
        if (!$resultCheck || $resultCheck->num_rows === 0) {
            $this->response = [
                'status' => 'error',
                'message' => 'El producto no existe o ya fue eliminado'
            ];
            if ($resultCheck) $resultCheck->free();
            return;
        }
        
        $producto = $resultCheck->fetch_assoc();
        $resultCheck->free();
        
        // Realizar borrado lógico
        $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$idInt}";
        
        if ($this->conexion->query($sql)) {
            $this->response = [
                'status' => 'success',
                'message' => "Producto '{$producto['nombre']}' eliminado correctamente",
                'id' => $idInt
            ];
        } else {
            $this->response = [
                'status' => 'error',
                'message' => 'Error al eliminar el producto: ' . $this->conexion->error
            ];
        }
    }

    /**
     * Edita un producto existente
     * 
     * @param array $data Datos del producto con el ID
     * @return void
     */
    public function edit($data) {
        // Validar que se recibió el ID
        if (empty($data['id'])) {
            $this->response = [
                'status' => 'error',
                'message' => 'ID no proporcionado'
            ];
            return;
        }
        
        $idInt = intval($data['id']);
        
        // Verificar que el producto existe
        $sqlCheck = "SELECT id FROM productos WHERE id = {$idInt} AND eliminado = 0";
        $resultCheck = $this->conexion->query($sqlCheck);
        
        if (!$resultCheck || $resultCheck->num_rows === 0) {
            $this->response = [
                'status' => 'error',
                'message' => 'El producto no existe o fue eliminado'
            ];
            if ($resultCheck) $resultCheck->free();
            return;
        }
        $resultCheck->free();
        
        // Verificar que el nombre no esté usado por otro producto
        if (!empty($data['nombre'])) {
            $nombreEscaped = $this->conexion->real_escape_string(trim($data['nombre']));
            $sqlCheckNombre = "SELECT id FROM productos WHERE nombre = '{$nombreEscaped}' AND id != {$idInt} AND eliminado = 0";
            $resultCheckNombre = $this->conexion->query($sqlCheckNombre);
            
            if ($resultCheckNombre && $resultCheckNombre->num_rows > 0) {
                $this->response = [
                    'status' => 'error',
                    'message' => 'Ya existe otro producto con ese nombre'
                ];
                $resultCheckNombre->free();
                return;
            }
            if ($resultCheckNombre) $resultCheckNombre->free();
        }
        
        // Escapar y preparar datos
        $nombre = $this->conexion->real_escape_string(trim($data['nombre']));
        $marca = $this->conexion->real_escape_string(trim($data['marca']));
        $modelo = $this->conexion->real_escape_string(trim($data['modelo']));
        $precio = floatval($data['precio']);
        $unidades = intval($data['unidades']);
        $detalles = isset($data['detalles']) ? $this->conexion->real_escape_string(trim($data['detalles'])) : 'NA';
        $imagen = isset($data['imagen']) && !empty($data['imagen']) ? $this->conexion->real_escape_string(trim($data['imagen'])) : 'img/default.png';
        
        // Actualizar producto
        $sql = "UPDATE productos SET 
                nombre = '{$nombre}', 
                marca = '{$marca}', 
                modelo = '{$modelo}', 
                precio = {$precio}, 
                detalles = '{$detalles}', 
                unidades = {$unidades}, 
                imagen = '{$imagen}' 
                WHERE id = {$idInt}";
        
        if ($this->conexion->query($sql)) {
            $this->response = [
                'status' => 'success',
                'message' => "Producto '{$nombre}' actualizado correctamente",
                'id' => $idInt
            ];
        } else {
            $this->response = [
                'status' => 'error',
                'message' => 'Error al actualizar el producto: ' . $this->conexion->error
            ];
        }
    }

    /**
     * Obtiene los datos en formato JSON
     * 
     * @return string JSON con la respuesta
     */
    public function getData() {
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }
}
?>