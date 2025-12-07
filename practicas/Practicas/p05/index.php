<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Práctica 5 - Completa</title>
</head>
<body>

    <h2>Ejercicio 1: Validación de Variables</h2>
    <p>$_myvar, $_7var, myvar, $myvar, $var7, $_element1, $house*5</p>
    <?php
        echo '<h4>Respuesta:</h4>';
        echo '<ul>';
        echo '<li><code>$_myvar</code>: Válida.</li>';
        echo '<li><code>$_7var</code>: Válida.</li>';
        echo '<li><code>myvar</code>: Inválida, falta el $.</li>';
        echo '<li><code>$myvar</code>: Válida.</li>';
        echo '<li><code>$var7</code>: Válida.</li>';
        echo '<li><code>$_element1</code>: Válida.</li>';
        echo '<li><code>$house*5</code>: Inválida, contiene un *.</li>';
        echo '</ul>';
    ?>

    <hr>

    <h2>Ejercicio 2: Asignación y Referencias</h2>
    <?php
        // a. Mostrar contenido inicial
        echo '<h3>a. Contenido inicial de cada variable</h3>';
        $a = "ManejadorSQL";
        $b = 'MySQL';
        $c = &$a;
        echo "<p>Valor de \$a: $a</p>";
        echo "<p>Valor de \$b: $b</p>";
        echo "<p>Valor de \$c: $c</p>";

        // b. y c. Nuevas asignaciones y mostrar contenido
        echo '<h3>c. Contenido después de las nuevas asignaciones</h3>';
        $a = "PHP server";
        $b = &$a;
        echo "<p>Valor de \$a: $a</p>";
        echo "<p>Valor de \$b: $b</p>";
        echo "<p>Valor de \$c: $c</p>";

        // d. Describir lo que ocurrió
        echo '<h3>d. Descripción de lo ocurrido</h3>';
        echo '<p>Debido a la asignación por referencia (&), <code>$c</code> y luego <code>$b</code> terminaron apuntando al mismo valor que <code>$a</code>, por lo que todas reflejan el último cambio de <code>$a</code>.</p>';
        
        // Liberar variables del ejercicio para evitar conflictos
        unset($a, $b, $c);
    ?>

    <hr>

    <h2>Ejercicio 3: Evolución de Variables y Tipos</h2>
    <?php
        echo '<h4>$a = "PHP5";</h4>';
        $a = "PHP5";
        var_dump($a);

        echo '<h4>$z[] = &$a;</h4>';
        $z[] = &$a;
        var_dump($z);

        echo '<h4>$b = "5a version de PHP";</h4>';
        $b = "5a version de PHP";
        var_dump($b);

        echo '<h4>$c = $b*10;</h4>';
        @$c = $b*10;
        var_dump($c);

        echo '<h4>$a .= $b;</h4>';
        $a .= $b;
        var_dump($a);

        echo '<h4>$b *= $c;</h4>';
        @$b *= $c;
        var_dump($b);

        echo '<h4>$z[0] = "MySQL";</h4>';
        $z[0] = "MySQL";
        var_dump($z);
        
        echo "<p><strong>Estado final de \$a (afectada por la referencia de \$z[0]):</strong></p>";
        var_dump($a);
    ?>

    <hr>

    <h2>Ejercicio 4: Acceso con $GLOBALS y global</h2>

    <h3>Accediendo con la matriz <code>$GLOBALS</code></h3>
    <?php
        echo "<p>Valor de \$GLOBALS['a']: ";
        var_dump($GLOBALS['a']);
        echo "</p>";

        echo "<p>Valor de \$GLOBALS['b']: ";
        var_dump($GLOBALS['b']);
        echo "</p>";

        echo "<p>Valor de \$GLOBALS['c']: ";
        var_dump($GLOBALS['c']);
        echo "</p>";

        echo "<p>Valor de \$GLOBALS['z']:</p>";
        var_dump($GLOBALS['z']);
    ?>

    <h3>Accediendo con el modificador <code>global</code></h3>
    <?php
        function mostrarGlobales() {
            global $a, $b, $c, $z;
            echo "<p>Valor de \$a (desde la función):</p>";
            var_dump($a);
            echo "<p>Valor de \$b (desde la función):</p>";
            var_dump($b);
        }

        mostrarGlobales();
        
        // Liberar variables al final
        unset($a, $b, $c, $z);
    ?>
    

    <hr>

<h2>Ejercicio 5: Casting de Tipos</h2>
<?php
    $a = "7 personas";
    $b = (integer) $a;
    $a = "9E3";
    $c = (double) $a;

    echo "<p>Después del script, los valores finales son:</p>";
    echo "Valor final de \$a:";
    var_dump($a); // string(3) "9E3"
    
    echo "Valor final de \$b:";
    var_dump($b); // int(7)
    
    echo "Valor final de \$c:";
    var_dump($c); // float(9000)

    // Liberar variables
    unset($a, $b, $c);
?>

<hr>

<h2>Ejercicio 6: Valores Booleanos</h2>
<?php
    $a = "0";
    $b = "TRUE";
    $c = FALSE;
    $d = ($a OR $b);
    $e = ($a AND $c);
    $f = ($a XOR $b);

    echo "<p>Comprobación del valor booleano de cada variable:</p>";
    echo "Variable \$a:"; var_dump((bool)$a);
    echo "Variable \$b:"; var_dump((bool)$b);
    echo "Variable \$c:"; var_dump((bool)$c);
    echo "Variable \$d:"; var_dump((bool)$d);
    echo "Variable \$e:"; var_dump((bool)$e);
    echo "Variable \$f:"; var_dump((bool)$f);

    echo "<h3>Transformar booleano para mostrar con 'echo'</h3>";
    
    // Usando un operador ternario, que es una forma común y legible
    $c_texto = ($c ? 'true' : 'false');
    $e_texto = ($e ? 'true' : 'false');

    echo "<p>La función <code>var_export()</code> también puede ser útil, o una estructura simple como un operador ternario.</p>";
    echo "<p>El valor de \$c como texto es: <strong>$c_texto</strong></p>";
    echo "<p>El valor de \$e como texto es: <strong>$e_texto</strong></p>";
    
    // Liberar variables
    unset($a, $b, $c, $d, $e, $f, $c_texto, $e_texto);
?>

<hr>

<h2>Ejercicio 7: Uso de la variable $_SERVER</h2>
<?php
    // a. La versión de Apache y PHP
    // La variable 'SERVER_SOFTWARE' usualmente contiene ambos datos.
    $software_servidor = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'No disponible';
    echo "<p><strong>a. Versión de Apache y PHP:</strong> " . htmlspecialchars($software_servidor) . "</p>";

    // b. El nombre del sistema operativo (servidor)
    // La función php_uname() es más específica para el SO.
    $sistema_operativo = php_uname();
    echo "<p><strong>b. Sistema operativo del servidor:</strong> " . htmlspecialchars($sistema_operativo) . "</p>";

    // c. El idioma del navegador (cliente)
    // Se obtiene de las cabeceras que envía el navegador.
    $idioma_navegador = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'No disponible';
    echo "<p><strong>c. Idioma del navegador del cliente:</strong> " . htmlspecialchars($idioma_navegador) . "</p>";
?>
</body>
</html>