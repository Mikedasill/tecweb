$(document).ready(function() {
    let edit = false;
    let nombreDisponible = true;
    let validationState = {
        name: false,
        marca: false,
        modelo: false,
        precio: false,
        detalles: true,  // Opcional, por defecto válido
        unidades: false,
        imagen: true     // Opcional, por defecto válido
    };

    // Inicialización - ASEGURAR QUE productId ESTÉ VACÍO
    $('#product-result').hide();
    $('#productId').val(''); // Limpiar ID oculto
    listarProductos();

    // ========== FUNCIONES DE VALIDACIÓN ==========
    
    function validarNombre(nombre) {
        if (!nombre || nombre.trim() === '') {
            return { valid: false, message: 'El nombre es requerido' };
        }
        if (nombre.length > 100) {
            return { valid: false, message: 'El nombre no puede exceder 100 caracteres' };
        }
        return { valid: true, message: 'Nombre válido' };
    }

    function validarMarca(marca) {
        if (!marca || marca.trim() === '') {
            return { valid: false, message: 'La marca es requerida' };
        }
        return { valid: true, message: 'Marca válida' };
    }

    function validarModelo(modelo) {
        if (!modelo || modelo.trim() === '') {
            return { valid: false, message: 'El modelo es requerido' };
        }
        const regex = /^[a-zA-Z0-9\-]+$/;
        if (!regex.test(modelo)) {
            return { valid: false, message: 'El modelo solo puede contener letras, números y guiones' };
        }
        if (modelo.length > 25) {
            return { valid: false, message: 'El modelo no puede exceder 25 caracteres' };
        }
        return { valid: true, message: 'Modelo válido' };
    }

    function validarPrecio(precio) {
        if (!precio || precio === '') {
            return { valid: false, message: 'El precio es requerido' };
        }
        const precioNum = parseFloat(precio);
        if (isNaN(precioNum)) {
            return { valid: false, message: 'El precio debe ser un número válido' };
        }
        if (precioNum < 0.01 || precioNum > 99.99) {
            return { valid: false, message: 'El precio debe estar entre 0.01 y 99.99' };
        }
        return { valid: true, message: 'Precio válido' };
    }

    function validarDetalles(detalles) {
        if (detalles && detalles.length > 250) {
            return { valid: false, message: 'Los detalles no pueden exceder 250 caracteres' };
        }
        return { valid: true, message: detalles ? 'Detalles válidos' : '' };
    }

    function validarUnidades(unidades) {
        if (!unidades && unidades !== '0') {
            return { valid: false, message: 'Las unidades son requeridas' };
        }
        const unidadesNum = parseInt(unidades);
        if (isNaN(unidadesNum) || unidadesNum < 0) {
            return { valid: false, message: 'Las unidades deben ser un número mayor o igual a 0' };
        }
        return { valid: true, message: 'Unidades válidas' };
    }

    function validarImagen(imagen) {
        if (!imagen || imagen.trim() === '') {
            return { valid: true, message: '' };
        }
        return { valid: true, message: 'Ruta de imagen válida' };
    }

    function mostrarEstadoCampo(campo, resultado) {
        const statusElement = $(`#${campo}-status`);
        const inputElement = $(`#${campo}`);
        
        if (resultado.valid) {
            inputElement.removeClass('is-invalid').addClass('is-valid');
            statusElement.removeClass('text-danger').addClass('text-success');
        } else {
            inputElement.removeClass('is-valid').addClass('is-invalid');
            statusElement.removeClass('text-success').addClass('text-danger');
        }
        
        statusElement.text(resultado.message);
        validationState[campo] = resultado.valid;
    }

    function verificarNombreDisponible(nombre) {
        if (!nombre || nombre.trim() === '') return;
        
        $.ajax({
            url: './backend/product-search.php',
            type: 'GET',
            data: { search: nombre },
            success: function(response) {
                const productos = JSON.parse(response);
                const productIdActual = $('#productId').val();
                const esEdicion = edit === true && productIdActual && productIdActual !== '';
                
                console.log('=== DEBUG VERIFICACIÓN NOMBRE ===');
                console.log('Nombre a verificar:', nombre);
                console.log('Modo edición?:', edit);
                console.log('Product ID actual:', productIdActual);
                console.log('Es edición?:', esEdicion);
                console.log('Productos encontrados:', productos);
                
                // Buscar coincidencia EXACTA del nombre (case insensitive)
                let duplicadoEncontrado = false;
                
                for(let i = 0; i < productos.length; i++) {
                    const prod = productos[i];
                    const nombreIgual = prod.nombre.trim().toLowerCase() === nombre.trim().toLowerCase();
                    
                    console.log(`Comparando con producto ID ${prod.id}: "${prod.nombre}"`);
                    console.log(`  - Nombres iguales?: ${nombreIgual}`);
                    
                    if(nombreIgual) {
                        // Si NO estamos en modo edición, cualquier coincidencia es duplicado
                        if(!esEdicion) {
                            console.log('  ✗ DUPLICADO ENCONTRADO (modo agregar)');
                            duplicadoEncontrado = true;
                            break;
                        }
                        // Si estamos editando, solo es duplicado si es OTRO producto
                        else if(prod.id != productIdActual) {
                            console.log('  ✗ DUPLICADO ENCONTRADO (otro producto con mismo nombre)');
                            duplicadoEncontrado = true;
                            break;
                        } else {
                            console.log('  ✓ Es el mismo producto que estamos editando, OK');
                        }
                    }
                }
                
                console.log('Resultado final: Duplicado?', duplicadoEncontrado);
                console.log('=================================');
                
                if (duplicadoEncontrado) {
                    nombreDisponible = false;
                    $('#name').removeClass('is-valid').addClass('is-invalid');
                    $('#name-status')
                        .removeClass('text-success')
                        .addClass('text-danger')
                        .text('⚠️ Este nombre ya existe en la base de datos');
                    validationState.name = false;
                } else {
                    nombreDisponible = true;
                    const validacion = validarNombre(nombre);
                    mostrarEstadoCampo('name', validacion);
                }
            },
            error: function() {
                console.error('Error al verificar nombre');
            }
        });
    }

    // ========== EVENTOS DE VALIDACIÓN ==========

    // Validación al perder el foco (blur)
    $('#name').on('blur', function() {
        const nombre = $(this).val();
        const validacion = validarNombre(nombre);
        mostrarEstadoCampo('name', validacion);
        if (validacion.valid) {
            verificarNombreDisponible(nombre);
        }
    });

    // Validación mientras se escribe (con debounce para nombre)
    let nombreTimeout;
    $('#name').on('input', function() {
        clearTimeout(nombreTimeout);
        nombreTimeout = setTimeout(() => {
            const nombre = $(this).val();
            if (nombre.trim() !== '') {
                verificarNombreDisponible(nombre);
            }
        }, 500);
    });

    $('#marca').on('blur', function() {
        mostrarEstadoCampo('marca', validarMarca($(this).val()));
    });

    $('#modelo').on('blur', function() {
        mostrarEstadoCampo('modelo', validarModelo($(this).val()));
    });

    $('#precio').on('blur', function() {
        mostrarEstadoCampo('precio', validarPrecio($(this).val()));
    });

    $('#detalles').on('blur', function() {
        mostrarEstadoCampo('detalles', validarDetalles($(this).val()));
    });

    $('#unidades').on('blur', function() {
        mostrarEstadoCampo('unidades', validarUnidades($(this).val()));
    });

    $('#imagen').on('blur', function() {
        mostrarEstadoCampo('imagen', validarImagen($(this).val()));
    });

    // ========== LISTAR PRODUCTOS ==========
    
    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                let productos;
                try {
                    productos = typeof response === 'string' ? JSON.parse(response) : response;
                } catch(e) {
                    console.error('Error al parsear productos:', e);
                    return;
                }
            
                if(Array.isArray(productos) && productos.length > 0) {
                    let template = '';

                    productos.forEach(producto => {
                        let descripcion = '';
                        descripcion += '<li>precio: $'+producto.precio+'</li>';
                        descripcion += '<li>unidades: '+producto.unidades+'</li>';
                        descripcion += '<li>modelo: '+producto.modelo+'</li>';
                        descripcion += '<li>marca: '+producto.marca+'</li>';
                        descripcion += '<li>detalles: '+producto.detalles+'</li>';
                    
                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td><ul>${descripcion}</ul></td>
                                <td>
                                    <button class="product-delete btn btn-danger btn-sm">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#products').html(template);
                } else {
                    $('#products').html('<tr><td colspan="4" class="text-center">No hay productos disponibles</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al listar productos:', error);
                $('#products').html('<tr><td colspan="4" class="text-center text-danger">Error al cargar productos</td></tr>');
            }
        });
    }

    // ========== BÚSQUEDA DE PRODUCTOS ==========
    
    $('#search').keyup(function() {
        const searchValue = $(this).val();
        
        if(searchValue) {
            $.ajax({
                url: './backend/product-search.php',
                data: { search: searchValue },
                type: 'GET',
                success: function(response) {
                    let productos;
                    try {
                        productos = typeof response === 'string' ? JSON.parse(response) : response;
                    } catch(e) {
                        console.error('Error al parsear búsqueda:', e);
                        return;
                    }
                    
                    if(Array.isArray(productos) && productos.length > 0) {
                        let template = '';
                        let template_bar = '';

                        productos.forEach(producto => {
                            let descripcion = '';
                            descripcion += '<li>precio: $'+producto.precio+'</li>';
                            descripcion += '<li>unidades: '+producto.unidades+'</li>';
                            descripcion += '<li>modelo: '+producto.modelo+'</li>';
                            descripcion += '<li>marca: '+producto.marca+'</li>';
                            descripcion += '<li>detalles: '+producto.detalles+'</li>';
                        
                            template += `
                                <tr productId="${producto.id}">
                                    <td>${producto.id}</td>
                                    <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                    <td><ul>${descripcion}</ul></td>
                                    <td>
                                        <button class="product-delete btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            `;

                            template_bar += `<li>${producto.nombre}</li>`;
                        });
                        
                        $('#product-result').show();
                        $('#container').html(template_bar);
                        $('#products').html(template);
                    } else {
                        $('#product-result').show();
                        $('#container').html('<li style="list-style: none;">No se encontraron productos</li>');
                        $('#products').html('<tr><td colspan="4" class="text-center">Sin resultados</td></tr>');
                    }
                },
                error: function() {
                    console.error('Error en la búsqueda');
                }
            });
        } else {
            $('#product-result').hide();
            listarProductos();
        }
    });

    // ========== ENVIAR FORMULARIO (AGREGAR/EDITAR) ==========
    
    $('#product-form').submit(function(e) {
        e.preventDefault();

        // Validar todos los campos antes de enviar
        const validaciones = {
            name: validarNombre($('#name').val()),
            marca: validarMarca($('#marca').val()),
            modelo: validarModelo($('#modelo').val()),
            precio: validarPrecio($('#precio').val()),
            detalles: validarDetalles($('#detalles').val()),
            unidades: validarUnidades($('#unidades').val()),
            imagen: validarImagen($('#imagen').val())
        };

        // Mostrar errores de validación
        let hayErrores = false;
        for (let campo in validaciones) {
            mostrarEstadoCampo(campo, validaciones[campo]);
            if (!validaciones[campo].valid) {
                hayErrores = true;
            }
        }

        if (hayErrores) {
            $('#validation-status')
                .removeClass('success').addClass('error show')
                .text('⚠️ Por favor corrige los errores en el formulario');
            return;
        }

        if (!nombreDisponible && !edit) {
            $('#validation-status')
                .removeClass('success').addClass('error show')
                .text('⚠️ El nombre del producto ya existe');
            return;
        }

        // Crear objeto con los datos del formulario
        const postData = {
            nombre: $('#name').val().trim(),
            marca: $('#marca').val().trim(),
            modelo: $('#modelo').val().trim(),
            precio: $('#precio').val(),
            detalles: $('#detalles').val().trim() || 'NA',
            unidades: $('#unidades').val(),
            imagen: $('#imagen').val().trim() || 'img/default.png'
        };

        // Si estamos editando, agregar el ID
        if (edit) {
            postData.id = $('#productId').val();
        }

        const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';
        
        // DEBUG: Mostrar datos que se enviarán
        console.log('Datos a enviar:', postData);
        console.log('URL:', url);

        $.post(url, postData, function(response) {
            // DEBUG: Mostrar respuesta del servidor
            console.log('Respuesta del servidor:', response);
            
            let respuesta;
            try {
                respuesta = typeof response === 'string' ? JSON.parse(response) : response;
            } catch(e) {
                console.error('Error al parsear respuesta:', e);
                console.error('Respuesta raw:', response);
                respuesta = { status: 'error', message: 'Error en la respuesta del servidor: ' + e.message };
            }

            let template_bar = `
                <li style="list-style: none;">status: ${respuesta.status}</li>
                <li style="list-style: none;">message: ${respuesta.message}</li>
            `;
            
            // Mostrar resultado
            $('#product-result').show();
            $('#container').html(template_bar);
            
            // Actualizar barra de estado general
            if (respuesta.status === 'success') {
                // Limpiar formulario solo si fue exitoso
                $('#product-form')[0].reset();
                $('#productId').val('');
                $('#imagen').val('img/default.png');
                $('.form-control').removeClass('is-valid is-invalid');
                $('.validation-message').text('');
                
                $('#validation-status')
                    .removeClass('error').addClass('success show')
                    .text('✓ ' + respuesta.message);
                $('#submitBtn').text('Agregar Producto');
                
                // Recargar lista de productos
                listarProductos();
                
                // Resetear bandera de edición
                edit = false;
                nombreDisponible = true;
            } else {
                // Si hay error, NO limpiar el formulario
                $('#validation-status')
                    .removeClass('success').addClass('error show')
                    .text('✗ ' + respuesta.message);
            }
            
            // Ocultar barra de estado después de 5 segundos
            setTimeout(() => {
                $('#validation-status').removeClass('show');
            }, 5000);
        }).fail(function(xhr, status, error) {
            console.error('Error AJAX:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
            
            let errorMsg = 'Error de conexión con el servidor';
            if (xhr.responseText) {
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    errorMsg = errorResponse.message || errorMsg;
                } catch(e) {
                    errorMsg += ': ' + xhr.responseText.substring(0, 100);
                }
            }
            
            $('#product-result').show();
            $('#container').html(`
                <li style="list-style: none;">status: error</li>
                <li style="list-style: none;">message: ${errorMsg}</li>
            `);
            
            $('#validation-status')
                .removeClass('success').addClass('error show')
                .text('✗ ' + errorMsg);
        });
    });

    // ========== ELIMINAR PRODUCTO ==========
    
    $(document).on('click', '.product-delete', function(e) {
        e.preventDefault();
        
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            // Usar this directamente para obtener el elemento correcto
            const element = $(this).closest('tr');
            const id = element.attr('productId');
            
            console.log('Eliminando producto ID:', id);
            
            if(!id) {
                alert('Error: No se pudo obtener el ID del producto');
                return;
            }
            
            $.post('./backend/product-delete.php', {id}, function(response) {
                console.log('Respuesta de eliminación:', response);
                
                let respuesta;
                try {
                    respuesta = typeof response === 'string' ? JSON.parse(response) : response;
                } catch(e) {
                    console.error('Error al parsear respuesta de eliminación:', e);
                    console.error('Respuesta raw:', response);
                    respuesta = { status: 'error', message: 'Error en la respuesta del servidor' };
                }

                let template_bar = `
                    <li style="list-style: none;">status: ${respuesta.status}</li>
                    <li style="list-style: none;">message: ${respuesta.message}</li>
                `;
                
                $('#product-result').show();
                $('#container').html(template_bar);
                
                if(respuesta.status === 'success') {
                    // Recargar lista solo si fue exitoso
                    listarProductos();
                    
                    // Ocultar mensaje después de 3 segundos
                    setTimeout(() => {
                        $('#product-result').hide();
                    }, 3000);
                } else {
                    // Mostrar error por más tiempo
                    setTimeout(() => {
                        $('#product-result').hide();
                    }, 5000);
                }
            }).fail(function(xhr, status, error) {
                console.error('Error AJAX al eliminar:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                alert('Error al eliminar el producto: ' + (xhr.responseText || error));
            });
        }
    });

    // ========== EDITAR PRODUCTO ==========
    
    $(document).on('click', '.product-item', function(e) {
        e.preventDefault();
        
        const element = $(this).closest('tr');
        const id = $(element).attr('productId');
        
        $.post('./backend/product-single.php', {id}, function(response) {
            let product;
            try {
                product = typeof response === 'string' ? JSON.parse(response) : response;
            } catch(e) {
                console.error('Error al parsear producto:', e);
                alert('Error al cargar el producto');
                return;
            }

            // Verificar si hay error en la respuesta
            if (product.status === 'error') {
                alert(product.message);
                return;
            }
            
            // Llenar el formulario con los datos del producto
            $('#name').val(product.nombre);
            $('#marca').val(product.marca);
            $('#modelo').val(product.modelo);
            $('#precio').val(product.precio);
            $('#detalles').val(product.detalles);
            $('#unidades').val(product.unidades);
            $('#imagen').val(product.imagen);
            $('#productId').val(product.id);
            
            // Cambiar texto del botón
            $('#submitBtn').text('Actualizar Producto');
            
            // Activar modo edición
            edit = true;
            nombreDisponible = true;
            
            // Validar todos los campos cargados
            mostrarEstadoCampo('name', validarNombre(product.nombre));
            mostrarEstadoCampo('marca', validarMarca(product.marca));
            mostrarEstadoCampo('modelo', validarModelo(product.modelo));
            mostrarEstadoCampo('precio', validarPrecio(product.precio));
            mostrarEstadoCampo('detalles', validarDetalles(product.detalles));
            mostrarEstadoCampo('unidades', validarUnidades(product.unidades));
            mostrarEstadoCampo('imagen', validarImagen(product.imagen));

            // Scroll al formulario
            $('html, body').animate({
                scrollTop: $("#product-form").offset().top - 20
            }, 500);
        }).fail(function() {
            alert('Error al cargar el producto');
        });
    });
});
