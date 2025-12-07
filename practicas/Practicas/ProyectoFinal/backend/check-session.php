<?php
    session_start();
    // Si existe user_id, devuelve true, si no, false
    if(isset($_SESSION['user_id'])) {
        echo json_encode(['logged' => true, 'name' => $_SESSION['user_name']]);
    } else {
        echo json_encode(['logged' => false]);
    }
?>