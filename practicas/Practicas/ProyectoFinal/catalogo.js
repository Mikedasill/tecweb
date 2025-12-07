// Cuando cargue la página
$(document).ready(function () {

    // Cargar todos los recursos al inicio
    cargarRecursos();

    // Buscar mientras escribe en la caja
    $('#search-catalogo').on('keyup', function () {
        let q = $(this).val().trim();

        if (q === '') {
            $('#status-bar').text('Mostrando todos los recursos');
            cargarRecursos();
        } else {
            buscarRecursos(q);
        }
    });
});

// ================= FUNCIONES =================

function cargarRecursos() {
    $.ajax({
        url: './backend/product-list.php',
        type: 'GET',
        success: function (response) {
            let recursos = JSON.parse(response);
            actualizarBarraEstado(recursos.length, null);
            pintarTarjetas(recursos);
        }
    });
}

function buscarRecursos(q) {
    $.ajax({
        url: './backend/product-search.php',
        type: 'GET',
        data: { search: q },
        success: function (response) {
            let recursos = JSON.parse(response);

            if (recursos.length === 0) {
                $('#status-bar').text('No se encontraron recursos para "' + q + '"');
                $('#cards-container').html('');
            } else {
                actualizarBarraEstado(recursos.length, q);
                pintarTarjetas(recursos);
            }
        }
    });
}

function actualizarBarraEstado(total, filtro) {
    if (filtro && filtro.trim() !== '') {
        $('#status-bar').text(
            'Resultados para "' + filtro + '": ' + total + ' recurso(s) encontrado(s)'
        );
    } else {
        $('#status-bar').text(
            'Mostrando todos los recursos (' + total + ' en total)'
        );
    }
}

function pintarTarjetas(lista) {
    let html = '';

    if (lista.length === 0) {
        $('#cards-container').html('');
        return;
    }

    lista.forEach(function (r) {
        const detalles = r.detalles && r.detalles.trim() !== ''
            ? r.detalles
            : 'Sin descripción';

        html += `
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
            <div class="card recurso-card h-100 bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="recurso-marca">${r.marca}</span>
                    <span class="badge badge-info recurso-modelo">${r.modelo}</span>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-2">${r.nombre}</h5>
                    <p class="card-text recurso-detalles flex-grow-1">
                        ${detalles}
                    </p>
                    <div class="recurso-meta mt-2">
                        <span class="badge badge-light">Precio: ${r.precio}</span>
                        <span class="badge badge-secondary">Unidades: ${r.unidades}</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 text-right">
                    <!-- Enlace mínimo para simular descarga del recurso -->
                    <a href="docs/recurso-demo.pdf"
                       target="_blank"
                       class="btn btn-sm btn-outline-info">
                        Descargar
                    </a>
                </div>
            </div>
        </div>`;
    });

    $('#cards-container').html(html);
}

