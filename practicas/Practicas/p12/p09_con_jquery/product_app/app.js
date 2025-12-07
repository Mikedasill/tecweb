// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
  };

function init() {
    /**
     * Convierte el JSON a string para poder mostrarlo
     * ver: https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Global_Objects/JSON
     */
    var JsonString = JSON.stringify(baseJSON,null,2);
    document.getElementById("description").value = JsonString;
}

// SE EJECUTA CUANDO EL DOM ESTÁ LISTO
$(document).ready(function() {
    
    // Variable para saber si estamos editando o agregando
    let edit = false;

    // Cargar JSON base en el formulario
    init();
    
    // Cargar lista de productos al iniciar
    fetchProducts();

    // --- MANEJADORES DE EVENTOS ---

    // 1. BÚSQUEDA EN VIVO (Lógica ii y iii)
    $('#search').keyup(function() {
        // Ocultar barra de estado si la búsqueda está vacía
        if($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: './backend/product-search.php',
                type: 'GET',
                data: { search }, // JQuery lo formatea como ?search=valor
                success: function(response) {
                    let productos = JSON.parse(response);
                    
                    if(Object.keys(productos).length > 0) {
                        let template = '';
                        let template_bar = '';

                        productos.forEach(producto => {
                            let descripcion = formatDescription(producto);
                        
                            template += `
                                <tr productId="${producto.id}">
                                    <td>${producto.id}</td>
                                    <td>${producto.nombre}</td>
                                    <td><ul>${descripcion}</ul></td>
                                    <td>
                                        <button class="product-item btn btn-info">
                                            Editar
                                        </button>
                                    </td>
                                    <td>
                                        <button class="product-delete btn btn-danger">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            `;
                            template_bar += `<li>${producto.nombre}</li>`;
                        });

                        $('#product-result').removeClass('d-none'); // Mostrar barra de estado
                        $('#container').html(template_bar);
                        $('#products').html(template);
                    }
                }
            });
        } else {
            $('#product-result').addClass('d-none'); // Ocultar barra
            fetchProducts(); // Mostrar todos los productos de nuevo
        }
    });

    // 2. AGREGAR O EDITAR PRODUCTO (Lógica iv y v)
    $('#product-form').submit(function(e) {
        e.preventDefault();

        // Obtener datos del formulario
        var productoJsonString = $('#description').val();
        var finalJSON = JSON.parse(productoJsonString);
        finalJSON['nombre'] = $('#name').val();

        // Decidir si es ADD o EDIT
        let url = edit ? './backend/product-edit.php' : './backend/product-add.php';
        
        if (edit) {
            finalJSON['id'] = $('#productId').val(); // Agregar ID si estamos editando
        }

        productoJsonString = JSON.stringify(finalJSON);

        $.ajax({
            url: url,
            type: 'POST',
            data: productoJsonString, // Enviar el JSON como string
            contentType: 'application/json;charset=UTF-8', // Indicar que enviamos JSON
            success: function(response) {
                console.log(response);
                let respuesta = JSON.parse(response);
                
                // Mostrar mensaje de estado (Lógica iv)
                let template_bar = `
                    <li style="list-style: none;">status: ${respuesta.status}</li>
                    <li style="list-style: none;">message: ${respuesta.message}</li>
                `;
                $('#product-result').removeClass('d-none');
                $('#container').html(template_bar);

                // Recargar lista de productos (Lógica v)
                fetchProducts();
                
                // Resetear formulario
                $('#product-form').trigger('reset');
                init(); // Recargar el JSON base en el textarea
                edit = false; // Resetear estado de edición
                $('#productId').val('');
            }
        });
    });

    // 3. ELIMINAR PRODUCTO (Lógica vi)
    // Se usa 'document' para delegar el evento a elementos futuros
    $(document).on('click', '.product-delete', function() {
        if(confirm('¿De verdad deseas eliminar el Producto?')) {
            // $(this) es el botón. .parent() es <td>. .parent() es <tr>
            let element = $(this).parent().parent();
            let id = $(element).attr('productId');
            
            $.ajax({
                url: './backend/product-delete.php',
                type: 'GET',
                data: { id },
                success: function(response) {
                    console.log(response);
                    let respuesta = JSON.parse(response);

                    // Mostrar mensaje de estado
                    let template_bar = `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;
                    $('#product-result').removeClass('d-none');
                    $('#container').html(template_bar);

                    // Recargar lista de productos (Lógica vi)
                    fetchProducts();
                }
            });
        }
    });

    // 4. SELECCIONAR PARA EDITAR (Nueva Funcionalidad)
    $(document).on('click', '.product-item', function() {
        let element = $(this).parent().parent();
        let id = $(element).attr('productId');

        $.ajax({
            url: './backend/product-single.php',
            type: 'GET',
            data: { id },
            success: function(response) {
                let producto = JSON.parse(response);
                
                // Llenar formulario
                $('#name').val(producto.nombre);
                $('#productId').val(producto.id); // Guardar ID en campo oculto
                
                // Crear JSON para el textarea (sin id, nombre, eliminado)
                let editJSON = {
                    "precio": parseFloat(producto.precio), // Asegurar que sea número
                    "unidades": parseInt(producto.unidades), // Asegurar que sea entero
                    "modelo": producto.modelo,
                    "marca": producto.marca,
                    "detalles": producto.detalles,
                    "imagen": producto.imagen
                };
                $('#description').val(JSON.stringify(editJSON, null, 2));

                // Marcar que estamos editando
                edit = true;
            }
        });
    });


    // --- FUNCIONES AUXILIARES ---

    // FUNCIÓN PARA LISTAR PRODUCTOS (Lógica i)
    function fetchProducts() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                let productos = JSON.parse(response);
                let template = '';
                
                productos.forEach(producto => {
                    let descripcion = formatDescription(producto);
                
                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-item btn btn-info">
                                    Editar
                                </button>
                            </td>
                            <td>
                                <button class="product-delete btn btn-danger">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                $('#products').html(template);
            }
        });
    }

    // Función para formatear la descripción del producto
    function formatDescription(producto) {
        let descripcion = '';
        descripcion += '<li>precio: '+producto.precio+'</li>';
        descripcion += '<li>unidades: '+producto.unidades+'</li>';
        descripcion += '<li>modelo: '+producto.modelo+'</li>';
        descripcion += '<li>marca: '+producto.marca+'</li>';
        descripcion += '<li>detalles: '+producto.detalles+'</li>';
        return descripcion;
    }

});