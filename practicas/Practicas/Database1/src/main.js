function getDatos() {
    var nombre = prompt("Nombre: ", "");
    var edad = prompt("Edad: ", 0);
    var div1 = document.getElementById('nombre');
    div1.innerHTML = '<h3> Nombre: ' + nombre + '</h3>';
    var div2 = document.getElementById('edad');
    div2.innerHTML = '<h3> Edad: ' + edad + '</h3>';
}

function ejemplo1() {
    // JS01_Introduccion_a_JavaScript.pdf: Ejemplo pág. 8
    // Este archivo no fue proporcionado.
    document.getElementById('ejemplo1').innerHTML = 'Ejemplo no disponible.';
}

function ejemplo2() {
    // JS02_Variables_Entradas_Operadores.pdf: Ejemplo pág. 6
    var nombre = 'Juan';
    var edad = 10;
    var altura = 1.92;
    var casado = false;
    var resultado = "Nombre: " + nombre + "<br>" +
                    "Edad: " + edad + "<br>" +
                    "Altura: " + altura + "<br>" +
                    "Casado: " + casado;
    document.getElementById('ejemplo2').innerHTML = resultado;
}

function ejemplo3() {
    // JS02_Variables_Entradas_Operadores.pdf: Ejemplo pág. 12
    var nombre = prompt('Ingresa tu nombre:', '');
    var edad = prompt('Ingresa tu edad:', '');
    var resultado = 'Hola ' + nombre + ' así que tienes ' + edad + ' años';
    document.getElementById('ejemplo3').innerHTML = resultado;
}

function ejemplo4() {
    // JS03_Estructuras_de_condicion.pdf: Ejemplo pág. 3
    var valor1 = prompt('Introducir primer número:', '');
    var valor2 = prompt('Introducir segundo número', '');
    var suma = parseInt(valor1) + parseInt(valor2);
    var producto = parseInt(valor1) * parseInt(valor2);
    var resultado = 'La suma es ' + suma + '<br>' + 'El producto es ' + producto;
    document.getElementById('ejemplo4').innerHTML = resultado;
}

function ejemplo5() {
    // JS03_Estructuras_de_condicion.pdf: Ejemplo pág. 8
    var nombre = prompt('Ingresa tu nombre:', '');
    var nota = prompt('Ingresa tu nota:', '');
    if (nota >= 4) {
        document.getElementById('ejemplo5').innerHTML = nombre + ' esta aprobado con un ' + nota;
    }
}

function ejemplo6() {
    // JS03_Estructuras_de_condicion.pdf: Ejemplo pág. 11
    var num1 = prompt('Ingresa el primer número:', '');
    var num2 = prompt('Ingresa el segundo número:', '');
    num1 = parseInt(num1);
    num2 = parseInt(num2);
    if (num1 > num2) {
        document.getElementById('ejemplo6').innerHTML = 'el mayor es ' + num1;
    } else {
        document.getElementById('ejemplo6').innerHTML = 'el mayor es ' + num2;
    }
}

function ejemplo7() {
    // JS03_Estructuras_de_condicion.pdf: Ejemplo pág. 15-16
    var nota1 = prompt('Ingresa 1ra. nota:', '');
    var nota2 = prompt('Ingresa 2da. nota:', '');
    var nota3 = prompt('Ingresa 3ra. nota:', '');
    nota1 = parseInt(nota1);
    nota2 = parseInt(nota2);
    nota3 = parseInt(nota3);
    var pro = (nota1 + nota2 + nota3) / 3;
    if (pro >= 7) {
        document.getElementById('ejemplo7').innerHTML = 'aprobado';
    } else {
        if (pro >= 4) {
            document.getElementById('ejemplo7').innerHTML = 'regular';
        } else {
            document.getElementById('ejemplo7').innerHTML = 'reprobado';
        }
    }
}

function ejemplo8() {
    // JS03_Estructuras_de_condicion.pdf: Ejemplo pág. 18
    var valor = prompt('Ingresar un valor comprendido entre 1 y 5:', '');
    valor = parseInt(valor);
    switch (valor) {
        case 1:
            document.getElementById('ejemplo8').innerHTML = 'uno';
            break;
        case 2:
            document.getElementById('ejemplo8').innerHTML = 'dos';
            break;
        case 3:
            document.getElementById('ejemplo8').innerHTML = 'tres';
            break;
        case 4:
            document.getElementById('ejemplo8').innerHTML = 'cuatro';
            break;
        case 5:
            document.getElementById('ejemplo8').innerHTML = 'cinco';
            break;
        default:
            document.getElementById('ejemplo8').innerHTML = 'debe ingresar un valor comprendido entre 1 y 5.';
    }
}

function ejemplo9() {
    // JS03_Estructuras_de_condicion.pdf: Ejemplo pág. 21
    var col = prompt('Ingresa el color con que quieras pintar el fondo de la ventana (rojo, verde, azul)', '');
    switch (col) {
        case 'rojo':
            document.body.style.backgroundColor = '#ff0000';
            break;
        case 'verde':
            document.body.style.backgroundColor = '#00ff00';
            break;
        case 'azul':
            document.body.style.backgroundColor = '#0000ff';
            break;
    }
}

function ejemplo10() {
    // JS04_Estructuras_de_repeticion.pdf: Ejemplo pág. 5
    var x = 1;
    var resultado = "";
    while (x <= 100) {
        resultado += x + "<br>";
        x = x + 1;
    }
    document.getElementById('ejemplo10').innerHTML = resultado;
}

function ejemplo11() {
    // JS04_Estructuras_de_repeticion.pdf: Ejemplo pág. 6
    var x = 1;
    var suma = 0;
    var valor;
    while (x <= 5) {
        valor = prompt('Ingresa el valor:', '');
        valor = parseInt(valor);
        suma = suma + valor;
        x = x + 1;
    }
    document.getElementById('ejemplo11').innerHTML = "La suma de los valores es " + suma + "<br>";
}

function ejemplo12() {
    // JS04_Estructuras_de_repeticion.pdf: Ejemplo pág. 12
    var valor;
    var resultado = "";
    do {
        valor = prompt('Ingresa un valor entre 0 y 999:', '');
        valor = parseInt(valor);
        resultado += 'El valor ' + valor + ' tiene ';
        if (valor < 10) {
            resultado += '1 dígito' + "<br>";
        } else if (valor < 100) {
            resultado += '2 dígitos' + "<br>";
        } else {
            resultado += '3 dígitos' + "<br>";
        }
    } while (valor != 0);
    document.getElementById('ejemplo12').innerHTML = resultado;
}

function ejemplo13() {
    // JS04_Estructuras_de_repeticion.pdf: Ejemplo pág. 16
    var f;
    var resultado = "";
    for (f = 1; f <= 10; f++) {
        resultado += f + " ";
    }
    document.getElementById('ejemplo13').innerHTML = resultado;
}

function ejemplo14() {
    // JS05_Funciones.pdf: Ejemplo pág. 6
    var resultado = "";
    function mostrarMensaje() {
        resultado += "Cuidado<br>";
        resultado += "Ingresa tu documento correctamente<br>";
    }
    mostrarMensaje();
    mostrarMensaje();
    mostrarMensaje();
    document.getElementById('ejemplo14').innerHTML = resultado;
}

function ejemplo15() {
    // JS05_Funciones.pdf: Ejemplo pág. 7
    function mostrarMensaje() {
        return "Cuidado<br>Ingresa tu documento correctamente<br>";
    }
    var resultado = mostrarMensaje() + mostrarMensaje() + mostrarMensaje();
    document.getElementById('ejemplo15').innerHTML = resultado;
}

function ejemplo16() {
    // JS05_Funciones.pdf: Ejemplo pág. 10
    function mostrarRango(x1, x2) {
        var resultado = "";
        for (var inicio = x1; inicio <= x2; inicio++) {
            resultado += inicio + ' ';
        }
        return resultado;
    }
    var valor1 = prompt('Ingresa el valor inferior:', '');
    valor1 = parseInt(valor1);
    var valor2 = prompt('Ingresa el valor superior:', '');
    valor2 = parseInt(valor2);
    document.getElementById('ejemplo16').innerHTML = mostrarRango(valor1, valor2);
}

function ejemplo17() {
    // JS05_Funciones.pdf: Ejemplo pág. 13
    function convertirCastellano(x) {
        if (x == 1) return "uno";
        else if (x == 2) return "dos";
        else if (x == 3) return "tres";
        else if (x == 4) return "cuatro";
        else if (x == 5) return "cinco";
        else return "valor incorrecto";
    }
    var valor = prompt("Ingresa un valor entre 1 y 5", "");
    valor = parseInt(valor);
    var r = convertirCastellano(valor);
    document.getElementById('ejemplo17').innerHTML = r;
}

function ejemplo18() {
    // JS05_Funciones.pdf: Ejemplo pág. 15
    function convertirCastellano(x) {
        switch (x) {
            case 1:
                return "uno";
            case 2:
                return "dos";
            case 3:
                return "tres";
            case 4:
                return "cuatro";
            case 5:
                return "cinco";
            default:
                return "valor incorrecto";
        }
    }
    var valor = prompt("Ingresa un valor entre 1 y 5", "");
    valor = parseInt(valor);
    var r = convertirCastellano(valor);
    document.getElementById('ejemplo18').innerHTML = r;
}