<?php
// practicas/p07/src/funciones.php

/* ===========================================================
   E1: ¿Es múltiplo de 5 y 7?
   =========================================================== */
function esMultiploDe5y7(int $n): bool {
    return ($n % 5 === 0) && ($n % 7 === 0);
}

/* ===========================================================
   E2: Generar filas de 3 números aleatorios hasta obtener
       la secuencia impar, par, impar en una fila.
   =========================================================== */
function generarHastaImparParImpar(int $min = 0, int $max = 999): array {
    $matriz = [];
    $iteraciones = 0;

    do {
        $a = random_int($min, $max);
        $b = random_int($min, $max);
        $c = random_int($min, $max);
        $fila = [$a, $b, $c];
        $matriz[] = $fila;
        $iteraciones++;
        $cumple = ($a % 2 !== 0) && ($b % 2 === 0) && ($c % 2 !== 0);
    } while (!$cumple);

    return [
        'matriz'      => $matriz,
        'iteraciones' => $iteraciones,
        'total'       => $iteraciones * 3,
    ];
}

/* ===========================================================
   E3: Encontrar el primer aleatorio múltiplo de un número dado
       (versión while) y (versión do-while)
   =========================================================== */
function primerMultiploWhile(int $divisor, int $min = 1, int $max = 1000): array {
    if ($divisor === 0) {
        return ['numero' => null, 'intentos' => 0];
    }
    $intentos = 0;
    $n = null;
    $encontrado = false;
    while (!$encontrado) {
        $n = random_int($min, $max);
        $intentos++;
        if ($n % $divisor === 0) $encontrado = true;
    }
    return ['numero' => $n, 'intentos' => $intentos];
}

function primerMultiploDoWhile(int $divisor, int $min = 1, int $max = 1000): array {
    if ($divisor === 0) {
        return ['numero' => null, 'intentos' => 0];
    }
    $intentos = 0;
    do {
        $n = random_int($min, $max);
        $intentos++;
    } while ($n % $divisor !== 0);
    return ['numero' => $n, 'intentos' => $intentos];
}

/* ===========================================================
   E4: Arreglo 97..122 => 'a'..'z' y devolverlo
   =========================================================== */
function asciiAZ(): array {
    $arr = [];
    for ($i = 97; $i <= 122; $i++) {
        $arr[$i] = chr($i);
    }
    return $arr;
}

/* ===========================================================
   E5: Validación de rango con POST
   =========================================================== */
function bienvenidaPorRango(?string $sexo, ?int $edad): string {
    if ($sexo === null || $edad === null) return "Datos incompletos.";
    if (strtolower($sexo) === 'f' && $edad >= 18 && $edad <= 35) {
        return "Bienvenida, usted está en el rango de edad permitido.";
    }
    return "Lo sentimos, no cumple la condición (sexo=f y edad 18–35).";
}

/* ===========================================================
   E6: Parque vehicular (arreglo 'hard-coded') + consultas
   Cada clave es la matrícula LLLNNNN (A–Z, 0–9).
   =========================================================== */
function parqueVehicular(): array {
    return [
        'ABC1234' => [
            'Auto' => ['marca' => 'HONDA', 'modelo' => 2020, 'tipo' => 'camioneta'],
            'Propietario' => ['nombre' => 'Ana López', 'ciudad' => 'Puebla', 'direccion' => 'C.U.']
        ],
        'BCD2345' => [
            'Auto' => ['marca' => 'MAZDA', 'modelo' => 2019, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Luis Pérez', 'ciudad' => 'CDMX', 'direccion' => 'Roma Norte']
        ],
        'CDE3456' => [
            'Auto' => ['marca' => 'NISSAN', 'modelo' => 2018, 'tipo' => 'hachback'],
            'Propietario' => ['nombre' => 'María Ruiz', 'ciudad' => 'Guadalajara', 'direccion' => 'Centro']
        ],
        'DEF4567' => [
            'Auto' => ['marca' => 'VW', 'modelo' => 2021, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Jorge Díaz', 'ciudad' => 'Monterrey', 'direccion' => 'Obispado']
        ],
        'EFG5678' => [
            'Auto' => ['marca' => 'KIA', 'modelo' => 2022, 'tipo' => 'camioneta'],
            'Propietario' => ['nombre' => 'Sara Neri', 'ciudad' => 'Puebla', 'direccion' => 'Angelópolis']
        ],
        'FGH6789' => [
            'Auto' => ['marca' => 'TOYOTA', 'modelo' => 2017, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Miguel Ochoa', 'ciudad' => 'León', 'direccion' => 'Centro']
        ],
        'GHI7890' => [
            'Auto' => ['marca' => 'FORD', 'modelo' => 2015, 'tipo' => 'camioneta'],
            'Propietario' => ['nombre' => 'Julia Ríos', 'ciudad' => 'Querétaro', 'direccion' => 'Juriquilla']
        ],
        'HIJ8901' => [
            'Auto' => ['marca' => 'CHEVROLET', 'modelo' => 2020, 'tipo' => 'hachback'],
            'Propietario' => ['nombre' => 'Iván Mora', 'ciudad' => 'Puebla', 'direccion' => 'Las Ánimas']
        ],
        'IJK9012' => [
            'Auto' => ['marca' => 'SEAT', 'modelo' => 2019, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Olga Vera', 'ciudad' => 'Toluca', 'direccion' => 'Centro']
        ],
        'JKL0123' => [
            'Auto' => ['marca' => 'BMW', 'modelo' => 2018, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Héctor Gil', 'ciudad' => 'CDMX', 'direccion' => 'Del Valle']
        ],
        'KLM1235' => [
            'Auto' => ['marca' => 'AUDI', 'modelo' => 2017, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Cecilia Mar', 'ciudad' => 'CDMX', 'direccion' => 'Polanco']
        ],
        'LMN2346' => [
            'Auto' => ['marca' => 'RENAULT', 'modelo' => 2016, 'tipo' => 'hachback'],
            'Propietario' => ['nombre' => 'Bruno Sol', 'ciudad' => 'Puebla', 'direccion' => 'Zavaleta']
        ],
        'MNO3457' => [
            'Auto' => ['marca' => 'PEUGEOT', 'modelo' => 2015, 'tipo' => 'sedan'],
            'Propietario' => ['nombre' => 'Tania Rey', 'ciudad' => 'Querétaro', 'direccion' => 'Tecnológico']
        ],
        'NOP4568' => [
            'Auto' => ['marca' => 'HONDA', 'modelo' => 2014, 'tipo' => 'hachback'],
            'Propietario' => ['nombre' => 'Oscar Paz', 'ciudad' => 'Monterrey', 'direccion' => 'San Pedro']
        ],
        'OPQ5679' => [
            'Auto' => ['marca' => 'TOYOTA', 'modelo' => 2013, 'tipo' => 'camioneta'],
            'Propietario' => ['nombre' => 'Lola Cruz', 'ciudad' => 'Guadalajara', 'direccion' => 'Providencia']
        ],
    ];
}

function buscarPorMatricula(array $parque, string $matricula): ?array {
    $matricula = strtoupper(trim($matricula));
    return $parque[$matricula] ?? null;
}
