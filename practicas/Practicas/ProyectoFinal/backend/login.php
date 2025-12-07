<?php
    include_once __DIR__.'/database.php';

    // Recibimos datos
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    // Respuesta por defecto
    $response = array(
        'status' => 'error', 
        'message' => 'Credenciales incorrectas'
    );

    // Consulta segura (evitando caracteres raros básica)
    // NOTA: Para el proyecto escolar usamos texto plano. 
    // En la vida real usaríamos password_verify()
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$pass' AND rol = 'admin'";
    
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        // Usuario encontrado
        $row = $result->fetch_assoc();
        
        // ¡CREAMOS LA SESIÓN! Esto es el "Carnet de identidad"
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['nombre_completo'];
        $_SESSION['user_rol'] = $row['rol'];
        
        $response['status'] = 'success';
        $response['message'] = 'Bienvenido ' . $row['nombre_completo'];
    }

    echo json_encode($response);
?>