// Verifica sesión al cargar el archivo
$.ajax({
    url: './backend/check-session.php',
    type: 'GET',
    success: function(response) {
        let session = JSON.parse(response);
        if (!session.logged) {
            window.location.href = 'login.html';
        } else {
            console.log("Bienvenido: " + session.name);
            $('#user-label').text('Bienvenido, ' + session.name);
        }
    }
});

// JSON base que se carga en el textarea
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

function init() {
    var JsonString = JSON.stringify(baseJSON, null, 2);
    document.getElementById("description").value = JsonString;
}

// Variables globales para las gráficas
let chartRecursos = null;
let chartUnidades = null;
let chartPrecios = null;

// SE EJECUTA CUANDO EL DOM ESTÁ LISTO
$(document).ready(function() {

    let edit = false;

    // Cargar JSON base
    init();

    // Cargar lista de productos
    fetchProducts();

    // Cargar estadísticas para las gráficas
    fetchStats();

    // ---------- BOTÓN CERRAR SESIÓN ----------
    $('#logout-btn').click(function () {
        // Destruir sesión desde backend (opcional) o solo redirigir
        window.location.href = 'login.html';
    });

    // ---------- BÚSQUEDA EN VIVO ----------
    $('#search').keyup(function() {
        if($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: './backend/product-search.php',
                type: 'GET',
                data: { search },
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
                                        <button class="product-item btn btn-info btn-sm">
                                            Editar
                                        </button>
                                    </td>
                                    <td>
                                        <button class="product-delete btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            `;
                            template_bar += `<li>${producto.nombre}</li>`;
                        });

                        $('#product-result').removeClass('d-none');
                        $('#container').html(template_bar);
                        $('#products').html(template);
                    }
                }
            });
        } else {
            $('#product-result').addClass('d-none');
            fetchProducts();
        }
    });

    // ---------- AGREGAR / EDITAR RECURSO ----------
    $('#product-form').submit(function(e) {
        e.preventDefault();

        var productoJsonString = $('#description').val();

        let finalJSON;
        try {
            finalJSON = JSON.parse(productoJsonString);
        } catch (err) {
            alert('El JSON no es válido. Revisa la sintaxis.');
            return;
        }

        finalJSON['nombre'] = $('#name').val();

        if (!finalJSON['nombre'] || finalJSON['nombre'].trim() === '') {
            alert('El nombre del recurso es obligatorio.');
            return;
        }

        let url = edit ? './backend/product-edit.php' : './backend/recurso-add.php';

        if (edit) {
            finalJSON['id'] = $('#productId').val();
        }

        productoJsonString = JSON.stringify(finalJSON);

        $.ajax({
            url: url,
            type: 'POST',
            data: productoJsonString,
            contentType: 'application/json;charset=UTF-8',
            success: function(response) {
                console.log(response);
                let respuesta = JSON.parse(response);

                let template_bar = `
                    <li style="list-style: none;">status: ${respuesta.status}</li>
                    <li style="list-style: none;">message: ${respuesta.message}</li>
                `;
                $('#product-result').removeClass('d-none');
                $('#container').html(template_bar);

                // Recargar tabla y gráficas
                fetchProducts();
                fetchStats();

                // Reset formulario
                $('#product-form').trigger('reset');
                init();
                edit = false;
                $('#productId').val('');
            }
        });
    });

    // ---------- ELIMINAR ----------
    $(document).on('click', '.product-delete', function() {
        if(confirm('¿De verdad deseas eliminar el Producto?')) {
            let element = $(this).parent().parent();
            let id = $(element).attr('productId');

            $.ajax({
                url: './backend/product-delete.php',
                type: 'GET',
                data: { id },
                success: function(response) {
                    console.log(response);
                    let respuesta = JSON.parse(response);

                    let template_bar = `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;
                    $('#product-result').removeClass('d-none');
                    $('#container').html(template_bar);

                    // Recargar tabla y gráficas
                    fetchProducts();
                    fetchStats();
                }
            });
        }
    });

    // ---------- SELECCIONAR PARA EDITAR ----------
    $(document).on('click', '.product-item', function() {
        let element = $(this).parent().parent();
        let id = $(element).attr('productId');

        $.ajax({
            url: './backend/product-single.php',
            type: 'GET',
            data: { id },
            success: function(response) {
                let producto = JSON.parse(response);

                $('#name').val(producto.nombre);
                $('#productId').val(producto.id);

                let editJSON = {
                    "precio": parseFloat(producto.precio),
                    "unidades": parseInt(producto.unidades),
                    "modelo": producto.modelo,
                    "marca": producto.marca,
                    "detalles": producto.detalles,
                    "imagen": producto.imagen
                };
                $('#description').val(JSON.stringify(editJSON, null, 2));

                edit = true;
            }
        });
    });

    // ---------- FUNCIONES AUXILIARES ----------

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
                                <button class="product-item btn btn-info btn-sm">
                                    Editar
                                </button>
                            </td>
                            <td>
                                <button class="product-delete btn btn-danger btn-sm">
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

    function formatDescription(producto) {
        let descripcion = '';
        descripcion += '<li>precio: ' + producto.precio + '</li>';
        descripcion += '<li>unidades: ' + producto.unidades + '</li>';
        descripcion += '<li>modelo: ' + producto.modelo + '</li>';
        descripcion += '<li>marca: ' + producto.marca + '</li>';
        descripcion += '<li>detalles: ' + producto.detalles + '</li>';
        return descripcion;
    }

    // --------- ESTADÍSTICAS PARA LAS GRÁFICAS ---------
    function fetchStats() {
        $.ajax({
            url: './backend/product-stats.php',
            type: 'GET',
            success: function(response) {
                let stats = JSON.parse(response);

                const labels = stats.labels || [];
                const totalRecursos = stats.total_recursos || [];
                const totalUnidades = stats.total_unidades || [];
                const promedioPrecio = stats.promedio_precio || [];

                drawCharts(labels, totalRecursos, totalUnidades, promedioPrecio);
            }
        });
    }

    function drawCharts(labels, totalRecursos, totalUnidades, promedioPrecio) {
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                x: {
                    ticks: { color: '#ffffff' }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#ffffff' }
                }
            }
        };

        // ---- Gráfica 1: Recursos por marca ----
        const ctx1 = document.getElementById('chartRecursosMarca').getContext('2d');
        if (chartRecursos) chartRecursos.destroy();
        chartRecursos = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Recursos',
                    data: totalRecursos
                }]
            },
            options: commonOptions
        });

        // ---- Gráfica 2: Unidades totales por marca ----
        const ctx2 = document.getElementById('chartUnidadesMarca').getContext('2d');
        if (chartUnidades) chartUnidades.destroy();
        chartUnidades = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Unidades',
                    data: totalUnidades
                }]
            },
            options: commonOptions
        });

        // ---- Gráfica 3: Precio promedio por marca ----
        const ctx3 = document.getElementById('chartPrecioPromedio').getContext('2d');
        if (chartPrecios) chartPrecios.destroy();
        chartPrecios = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Precio promedio',
                    data: promedioPrecio
                }]
            },
            options: commonOptions
        });
    }

});

