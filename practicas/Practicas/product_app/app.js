$(document).ready(function(){
    let edit = false;
    let isNameAvailable = false; // Para la validación asíncrona

    $('#product-result').hide();
    listarProductos();

    // --- FUNCIONES DE VALIDACIÓN (BASADAS EN P10) ---

    /**
     * Función auxiliar para mostrar/ocultar errores de validación.
     * Muestra el error en el div ".invalid-feedback" y en la barra de estado.
     */
    function setValidationStatus(fieldId, message, isValid) {
        const field = $(`#${fieldId}`);
        const errorField = $(`#${fieldId}-error`);

        if (isValid) {
            field.removeClass('is-invalid');
            errorField.hide();
        } else {
            field.addClass('is-invalid');
            errorField.text(message);
            errorField.show();
            // Mostrar también en la barra de estado principal (Instrucción 4)
            showStatusMessage(`Error en ${fieldId}: ${message}`, 'error');
        }
    }

    /**
     * Función para mostrar mensajes en la barra de estado principal (#product-result)
     */
    function showStatusMessage(message, status = 'success') {
        let template_bar = '';
        const color = (status === 'error') ? '#dc3545' : '#28a745'; // Rojo para error, Verde para éxito
        
        template_bar += `<li style="list-style: none; color: ${color};">status: ${status}</li>`;
        template_bar += `<li style="list-style: none; color: ${color};">message: ${message}</li>`;
        
        $('#product-result').show();
        $('#container').html(template_bar);
    }
    
    // --- VALIDACIONES EN BLUR (Instrucción 3.1) ---
    // Se ejecutan cuando el usuario sale del campo

    $('#nombre').blur(function() {
        const nombre = $(this).val().trim();
        if (nombre === '') {
            setValidationStatus('nombre', 'El nombre es obligatorio.', false);
        } else if (nombre.length > 100) {
            setValidationStatus('nombre', 'El nombre no debe exceder 100 caracteres.', false);
        } else {
            // Si es válido, disparamos el keyup para verificar disponibilidad
            $(this).keyup(); // Llama a la validación asíncrona
        }
    });

    $('#marca').blur(function() {
        const marca = $(this).val();
        if (marca === '') {
            setValidationStatus('marca', 'Debe seleccionar una marca.', false);
        } else {
            setValidationStatus('marca', '', true);
        }
    });

    $('#modelo').blur(function() {
        const modelo = $(this).val().trim();
        const modeloRegex = /^[a-zA-Z0-9]+$/; // Regla de P10: solo letras y números
        if (modelo === '') {
            setValidationStatus('modelo', 'El modelo es obligatorio.', false);
        } else if (modelo.length > 25) {
            setValidationStatus('modelo', 'El modelo no debe exceder 25 caracteres.', false);
        } else if (!modeloRegex.test(modelo)) {
            setValidationStatus('modelo', 'El modelo solo puede contener letras y números.', false);
        } else {
            setValidationStatus('modelo', '', true);
        }
    });

    $('#precio').blur(function() {
        const precioStr = $(this).val().trim();
        const precio = parseFloat(precioStr);
        if (precioStr === '' || isNaN(precio)) {
            setValidationStatus('precio', 'El precio es obligatorio y debe ser un número.', false);
        } else if (precio <= 99.99) { // Regla de P10: mayor a 99.99
            setValidationStatus('precio', 'El precio debe ser mayor a 99.99.', false);
        } else {
            setValidationStatus('precio', '', true);
        }
    });

    $('#unidades').blur(function() {
        const unidadesStr = $(this).val().trim();
        const unidades = parseInt(unidadesStr, 10);
        if (unidadesStr === '' || isNaN(unidades)) {
            setValidationStatus('unidades', 'Las unidades son obligatorias y deben ser un número entero.', false);
        } else if (unidades < 0) { // Regla de P10: mayor o igual a 0
            setValidationStatus('unidades', 'Las unidades deben ser mayor o igual a 0.', false);
        } else {
            setValidationStatus('unidades', '', true);
        }
    });

    $('#detalles').blur(function() {
        const detalles = $(this).val();
        if (detalles.length > 250) { // Regla de P10
            setValidationStatus('detalles', 'Los detalles no deben exceder 250 caracteres.', false);
        } else {
            setValidationStatus('detalles', '', true);
        }
    });

    // --- VALIDACIÓN ASÍNCRONA DE NOMBRE (Instrucción 5) ---
    // Se ejecuta mientras el usuario escribe
    $('#nombre').keyup(function() {
        const nombre = $(this).val().trim();
        const originalName = $(this).data('original-name'); // Nombre guardado al editar

        if (nombre === '') {
            setValidationStatus('nombre', 'El nombre es obligatorio.', false);
            isNameAvailable = false;
            return;
        }

        // Si estamos editando y el nombre no ha cambiado, es válido
        if (edit && nombre === originalName) {
            setValidationStatus('nombre', '', true);
            isNameAvailable = true;
            return;
        }

        // Si el nombre es nuevo o ha cambiado, verificar en la BD
        $.post('./backend/product-check-name.php', { nombre }, (response) => {
            const data = JSON.parse(response);
            if (data.exists) {
                setValidationStatus('nombre', 'Este nombre de producto ya existe.', false);
                isNameAvailable = false;
            } else {
                setValidationStatus('nombre', '', true); // Nombre válido y disponible
                isNameAvailable = true;
            }
        });
    });

    // --- FUNCIÓN PARA LISTAR PRODUCTOS ---
    function listarProductos() {
        $.ajax({
            url: './backend/product-list.php',
            type: 'GET',
            success: function(response) {
                const productos = JSON.parse(response);
                let template = '';
                if(Object.keys(productos).length > 0) {
                    productos.forEach(producto => {
                        let descripcion = '';
                        descripcion += '<li>precio: '+producto.precio+'</li>';
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
                                    <button class="product-delete btn btn-danger">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
                $('#products').html(template);
            }
        });
    }

    // --- BÚSQUEDA DE PRODUCTOS ---
    $('#search').keyup(function() {
        if($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: './backend/product-search.php?search='+$('#search').val(),
                data: {search},
                type: 'GET',
                success: function (response) {
                    if(!response.error) {
                        const productos = JSON.parse(response);
                        let template = '';
                        let template_bar = '';
                        if(Object.keys(productos).length > 0) {
                            productos.forEach(producto => {
                                let descripcion = '';
                                descripcion += '<li>precio: '+producto.precio+'</li>';
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
                                            <button class="product-delete btn btn-danger">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                template_bar += `<li>${producto.nombre}</il>`;
                            });
                            $('#product-result').show();
                            $('#container').html(template_bar);
                            $('#products').html(template);    
                        }
                    }
                }
            });
        }
        else {
            $('#product-result').hide();
            listarProductos(); // Opcional: recargar lista completa si la búsqueda está vacía
        }
    });

    /**
     * Función para limpiar el formulario y las validaciones
     */
    function resetForm() {
        $('#product-form').trigger('reset'); // Limpia los valores de los campos
        $('#productId').val('');
        
        // Quitar clases y mensajes de error
        $('#product-form .form-control').removeClass('is-invalid');
        $('.invalid-feedback').hide();
        
        edit = false;
        isNameAvailable = false; // Reinicia la validación del nombre
        $('#form-submit-btn').text('Agregar Producto'); // Restaura el texto del botón
        $('#nombre').data('original-name', ''); // Limpiar nombre original guardado
    }

    // --- ENVÍO DE FORMULARIO (AGREGAR/EDITAR) (MODIFICADO) ---
    $('#product-form').submit(e => {
        e.preventDefault();

        // VALIDACIÓN ANTES DE ENVIAR (Instrucción 3.2)
        // Disparar todos los eventos "blur" para forzar la validación de todos los campos
        $('#nombre').blur();
        $('#marca').blur();
        $('#modelo').blur();
        $('#precio').blur();
        $('#unidades').blur();
        $('#detalles').blur();

        // Verificar si hay algún campo inválido (buscando la clase .is-invalid)
        const hasErrors = $('#product-form .form-control.is-invalid').length > 0;

        if (hasErrors) {
            showStatusMessage('Por favor, corrija los errores en el formulario.', 'error');
            return; // Detiene el envío
        }

        // Verificar la validación asíncrona del nombre (ya debe estar en true si pasó el blur)
        if (!isNameAvailable) {
            showStatusMessage('El nombre del producto no es válido o ya existe.', 'error');
            return; // Detiene el envío
        }

        // Si todas las validaciones pasan, construir el objeto postData
        const postData = {
            nombre: $('#nombre').val(),
            marca: $('#marca').val(),
            modelo: $('#modelo').val(),
            precio: $('#precio').val(),
            detalles: $('#detalles').val(),
            unidades: $('#unidades').val(),
            imagen: $('#imagen').val() || 'img/default.png', // Valor por defecto si está vacío
            id: $('#productId').val()
        };

        const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';
        
        $.post(url, postData, (response) => {
            let respuesta = JSON.parse(response);
            
            if (respuesta.status === 'success') {
                showStatusMessage(respuesta.message, 'success');
                resetForm(); // Limpiar formulario
                listarProductos(); // Actualizar tabla
            } else {
                // Mostrar error del backend (ej. fallo de la BD)
                showStatusMessage(respuesta.message, 'error');
            }
        });
    });

    // --- ELIMINAR PRODUCTO ---
    // Se usa delegación de eventos en 'document' para los botones creados dinámicamente
    $(document).on('click', '.product-delete', (e) => {
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            // $(e.target) es el botón presionado
            const element = $(e.target).closest('tr'); // Sube al elemento <tr> padre
            const id = $(element).attr('productId'); // Obtiene el ID
            
            $.post('./backend/product-delete.php', {id}, (response) => {
                $('#product-result').hide();
                listarProductos(); // Recarga la lista
            });
        }
    });

    // --- SELECCIONAR PRODUCTO PARA EDITAR (MODIFICADO) ---
    $(document).on('click', '.product-item', (e) => {
        e.preventDefault();
        
        // $(e.target) es el enlace <a> presionado
        const element = $(e.target).closest('tr');
        const id = $(element).attr('productId');
        
        $.post('./backend/product-single.php', {id}, (response) => {
            let product = JSON.parse(response);
            
            // Limpiar validaciones anteriores
            resetForm();

            // Llenar los campos del formulario con los datos del producto
            $('#nombre').val(product.nombre);
            // Guardar el nombre original para la validación asíncrona
            $('#nombre').data('original-name', product.nombre); 
            $('#marca').val(product.marca);
            $('#modelo').val(product.modelo);
            $('#precio').val(product.precio);
            $('#unidades').val(product.unidades);
            $('#detalles').val(product.detalles);
            $('#imagen').val(product.imagen);
            $('#productId').val(product.id); // Poner el ID en el campo oculto
            
            // Poner la bandera de edición en true
            edit = true;
            isNameAvailable = true; // El nombre actual es válido para editar
            $('#form-submit-btn').text('Actualizar Producto'); // Cambiar texto del botón
        });
    });    
});