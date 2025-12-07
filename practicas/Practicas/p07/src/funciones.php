<?php
// src/funciones.php

// Ejercicio 1: comprobar si un número es múltiplo de 5 y 7
function esMultiplo5y7($numero) {
    if ($numero % 5 == 0 && $numero % 7 == 0) {
        return "El número $numero es múltiplo de 5 y 7.";
    } else {
        return "El número $numero NO es múltiplo de 5 y 7.";
    }
}

// Ejercicio 2: generar números aleatorios hasta obtener la secuencia impar, par, impar
function generarSecuenciaImparParImpar() {
    $matriz = [];
    $iteraciones = 0;

    while (true) {
        $fila = [];
        for ($i = 0; $i < 3; $i++) {
            $fila[] = rand(100, 999); // números de 3 cifras (como en el ejemplo)
        }
        $matriz[] = $fila;
        $iteraciones++;

        // Revisamos si cumple el patrón: impar, par, impar
        if ($fila[0] % 2 != 0 && $fila[1] % 2 == 0 && $fila[2] % 2 != 0) {
            break;
        }
    }

    // Cantidad total de números generados
    $totalNumeros = $iteraciones * 3;

    return [
        "matriz" => $matriz,
        "iteraciones" => $iteraciones,
        "totalNumeros" => $totalNumeros
    ];
}

// Ejercicio 3a: buscar con while el primer número múltiplo de $divisor
function encontrarMultiploWhile($divisor) {
    $contador = 0;
    $numero = null;

    while (true) {
        $contador++;
        $numero = rand(1, 1000);
        if ($numero % $divisor == 0) {
            break;
        }
    }

    return [
        "numero" => $numero,
        "intentos" => $contador
    ];
}

// Ejercicio 3b: buscar con do-while el primer número múltiplo de $divisor
function encontrarMultiploDoWhile($divisor) {
    $contador = 0;
    $numero = null;

    do {
        $contador++;
        $numero = rand(1, 1000);
    } while ($numero % $divisor != 0);

    return [
        "numero" => $numero,
        "intentos" => $contador
    ];
}


// Ejercicio 4: crear arreglo de códigos ASCII (97–122) con letras de 'a' a 'z'
function crearArregloAscii() {
    $arreglo = [];
    for ($i = 97; $i <= 122; $i++) {
        $arreglo[$i] = chr($i); // convierte el código ASCII en carácter
    }
    return $arreglo;
}

// Ejercicio 5: Validar edad y sexo
function validarEdadSexo($edad, $sexo) {
    if ($sexo === "femenino" && $edad >= 18 && $edad <= 35) {
        return "Bienvenida, usted está en el rango de edad permitido.";
    } else {
        return "Lo sentimos, no cumple con los requisitos de edad o sexo.";
    }
}

// Ejercicio 6: Registro de parque vehicular
function registroParqueVehicular() {
    // Arreglo asociativo con 15 autos
    $autos = [
        "ABC1234" => [
            "Auto" => ["marca" => "HONDA", "modelo" => 2020, "tipo" => "camioneta"],
            "Propietario" => ["nombre" => "Alfonzo Esparza", "ciudad" => "Puebla, Pue.", "direccion" => "C.U., Jardines de San Manuel"]
        ],
        "DEF5678" => [
            "Auto" => ["marca" => "MAZDA", "modelo" => 2019, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "Ma. del Consuelo Molina", "ciudad" => "Puebla, Pue.", "direccion" => "97 oriente"]
        ],
        "GHI9012" => [
            "Auto" => ["marca" => "TOYOTA", "modelo" => 2021, "tipo" => "hachback"],
            "Propietario" => ["nombre" => "Juan Pérez", "ciudad" => "Cholula, Pue.", "direccion" => "Av. Reforma 45"]
        ],
        "JKL3456" => [
            "Auto" => ["marca" => "NISSAN", "modelo" => 2022, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "María López", "ciudad" => "Puebla, Pue.", "direccion" => "Boulevard Atlixco 12"]
        ],
        "MNO7890" => [
            "Auto" => ["marca" => "FORD", "modelo" => 2018, "tipo" => "camioneta"],
            "Propietario" => ["nombre" => "Pedro Ramírez", "ciudad" => "Tehuacán, Pue.", "direccion" => "Calle 3 Sur 120"]
        ],
        "PQR2345" => [
            "Auto" => ["marca" => "CHEVROLET", "modelo" => 2020, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "Ana Torres", "ciudad" => "Puebla, Pue.", "direccion" => "Col. La Paz 7"]
        ],
        "STU6789" => [
            "Auto" => ["marca" => "KIA", "modelo" => 2021, "tipo" => "hachback"],
            "Propietario" => ["nombre" => "Luis García", "ciudad" => "Atlixco, Pue.", "direccion" => "Av. Morelos 55"]
        ],
        "VWX0123" => [
            "Auto" => ["marca" => "HYUNDAI", "modelo" => 2019, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "Sofía Martínez", "ciudad" => "Puebla, Pue.", "direccion" => "Calle 5 de Mayo 200"]
        ],
        "YZA4567" => [
            "Auto" => ["marca" => "VOLKSWAGEN", "modelo" => 2022, "tipo" => "camioneta"],
            "Propietario" => ["nombre" => "Carlos Hernández", "ciudad" => "Cholula, Pue.", "direccion" => "Av. 2 Poniente 33"]
        ],
        "BCD8901" => [
            "Auto" => ["marca" => "FIAT", "modelo" => 2018, "tipo" => "hachback"],
            "Propietario" => ["nombre" => "Verónica Ruiz", "ciudad" => "Puebla, Pue.", "direccion" => "Col. La Noria 14"]
        ],
        "EFG2345" => [
            "Auto" => ["marca" => "MITSUBISHI", "modelo" => 2020, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "Jorge Castillo", "ciudad" => "Puebla, Pue.", "direccion" => "Calle 10 Norte 66"]
        ],
        "HIJ6789" => [
            "Auto" => ["marca" => "SUZUKI", "modelo" => 2019, "tipo" => "hachback"],
            "Propietario" => ["nombre" => "Paula Vega", "ciudad" => "Tehuacán, Pue.", "direccion" => "Callejón del Sol 22"]
        ],
        "KLM0123" => [
            "Auto" => ["marca" => "JEEP", "modelo" => 2021, "tipo" => "camioneta"],
            "Propietario" => ["nombre" => "Ricardo Mendoza", "ciudad" => "Puebla, Pue.", "direccion" => "Av. Juárez 10"]
        ],
        "NOP4567" => [
            "Auto" => ["marca" => "HONDA", "modelo" => 2022, "tipo" => "sedan"],
            "Propietario" => ["nombre" => "Gabriela Flores", "ciudad" => "Atlixco, Pue.", "direccion" => "Calle Hidalgo 5"]
        ],
        "QRS8901" => [
            "Auto" => ["marca" => "FORD", "modelo" => 2019, "tipo" => "hachback"],
            "Propietario" => ["nombre" => "Miguel Ángel", "ciudad" => "Puebla, Pue.", "direccion" => "Av. Reforma 77"]
        ]
    ];

    return $autos;
}

// Función para consultar un auto por matrícula
function buscarAutoPorMatricula($matricula) {
    $autos = registroParqueVehicular();
    if (isset($autos[$matricula])) {
        return $autos[$matricula];
    } else {
        return null;
    }
}