<?php
include "src/funciones.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">

<head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
    <title>Práctica 4 Corregida</title>
    <style type="text/css">
        table {
            border: 1px solid #333;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Ejercicio 1</h2>
    <p>Escribir programa para comprobar si un número es un múltiplo de 5 y 7</p>
    <?php
    if (isset($_GET['numero'])) {
        $numero = intval($_GET['numero']);
        echo "<p>" . esMultiplo5y7($numero) . "</p>";
    } else {
        echo "<p>Pasa un número en la URL usando <code>?numero=</code>. Ejemplo: 
        <br /> <code>http://localhost/p07/index.php?numero=35</code></p>";
    }
    ?>


    <h2>Ejercicio 2: Secuencia impar, par, impar</h2>
    <?php
    $resultado = generarSecuenciaImparParImpar();
    $matriz = $resultado["matriz"];
    $iteraciones = $resultado["iteraciones"];
    $totalNumeros = $resultado["totalNumeros"];

    echo "<table>";
    foreach ($matriz as $fila) {
        echo "<tr>";
        foreach ($fila as $num) {
            echo "<td>$num</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    echo "<p>$totalNumeros números obtenidos en $iteraciones iteraciones.</p>";
    ?>


    <h2>Ejercicio 3: Buscar el primer múltiplo de un número dado</h2>
    <?php
    if (isset($_GET['divisor'])) {
        $divisor = intval($_GET['divisor']);

        $resultadoWhile = encontrarMultiploWhile($divisor);
        $resultadoDoWhile = encontrarMultiploDoWhile($divisor);

        echo "<p><strong>Con while:</strong> Se encontró el número {$resultadoWhile['numero']} en {$resultadoWhile['intentos']} intentos.</p>";
        echo "<p><strong>Con do-while:</strong> Se encontró el número {$resultadoDoWhile['numero']} en {$resultadoDoWhile['intentos']} intentos.</p>";
    } else {
        echo "<p>Pasa un divisor en la URL usando <code>?divisor=</code>. Ejemplo: 
        <br /> <code>http://localhost/p07/index.php?divisor=7</code></p>";
    }
    ?>

    <h2>Ejercicio 4: Arreglo de ASCII con letras a-z</h2>
    <?php
    $arreglo = crearArregloAscii();

    echo "<table>";
    echo "<tr><th>Índice</th><th>Valor</th></tr>";
    foreach ($arreglo as $key => $value) {
        echo "<tr><td>$key</td><td>$value</td></tr>";
    }
    echo "</table>";
    ?>

     <h2>Ejercicio 5: Validar edad y sexo</h2>
    <form method="post" action="index.php">
        <p>
            <label for="edad">Edad:</label>
            <input type="text" name="edad" id="edad" />
        </p>
        <p>
            <label for="sexo">Sexo:</label>
            <select name="sexo" id="sexo">
                <option value="">--Seleccione--</option>
                <option value="femenino">Femenino</option>
                <option value="masculino">Masculino</option>
            </select>
        </p>
        <p>
            <input type="submit" name="ejercicio5" value="Enviar" />
        </p>
    </form>

    <?php
    if (isset($_POST['ejercicio5'])) {
        $edad = intval($_POST['edad']);
        $sexo = strtolower($_POST['sexo']);
        echo "<p>" . validarEdadSexo($edad, $sexo) . "</p>";
    }
    ?>

    <h2>Ejercicio 6: Parque Vehicular</h2>
    <form method="post" action="index.php">
        <p>
            <label for="matricula">Consultar por matrícula:</label>
            <input type="text" name="matricula" id="matricula" maxlength="7" />
            <input type="submit" name="buscar" value="Buscar" />
            <input type="submit" name="mostrarTodos" value="Mostrar todos" />
        </p>
    </form>

    <?php
    if (isset($_POST['buscar'])) {
        $matricula = strtoupper(trim($_POST['matricula']));
        $resultado = buscarAutoPorMatricula($matricula);
        if ($resultado) {
            echo "<pre>";
            print_r([$matricula => $resultado]);
            echo "</pre>";
        } else {
            echo "<p>No se encontró un auto con matrícula " . htmlspecialchars($matricula) . ".</p>";
        }
    }

    if (isset($_POST['mostrarTodos'])) {
        $todos = registroParqueVehicular();
        echo "<pre>";
        print_r($todos);
        echo "</pre>";
    }
    ?>

    <h2>Ejemplo de POST</h2>
    <form action="http://localhost/tecweb/practicas/p04/index.php" method="post">
        <p>
            <label>Name: <input type="text" name="name" /></label><br />
            <label>E-mail: <input type="text" name="email" /></label><br />
            <input type="submit" value="Submit" />
        </p>
    </form>
    
    <?php
        if(isset($_POST["name"]) && isset($_POST["email"]))
        {
            echo htmlspecialchars($_POST["name"]);
            echo '<br />';
            echo htmlspecialchars($_POST["email"]);
        }
    ?>

    <p>
  <a href="https://validator.w3.org/check?uri=referer"><img
    src="https://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
</p>
</body>
</html>