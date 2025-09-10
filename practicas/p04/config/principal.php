<?php
// Configuración de zona horaria
date_default_timezone_set('America/Mexico_City');
$fecha = date("d/m/Y H:i:s");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página principal con PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        header, footer {
            background: #222;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        section {
            margin: 20px 0;
            padding: 15px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        h1 {
            color: #444;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido a la página principal con PHP</h1>
        <p>Fecha actual: <strong><?php echo $fecha; ?></strong></p>
    </header>

    <main>
        <section>
            <h2>Notas</h2>
            <p>Ejemplo de cálculo con PHP:</p>
            <?php
                $nota1 = 8.5;
                $nota2 = 9.0;
                $nota3 = 7.8;
                $promedio = ($nota1 + $nota2 + $nota3) / 3;
                echo "<p>Notas: $nota1, $nota2, $nota3</p>";
                echo "<p>Promedio final: <strong>" . round($promedio,2) . "</strong></p>";
            ?>
        </section>
    </main>

    <footer>
        <p>Ejemplo de práctica con Apache + PHP - TecWeb</p>
    </footer>
</body>
</html>
