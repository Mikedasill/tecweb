// charts.js
$(document).ready(function () {
  // Cargar todas las gráficas cuando el dashboard esté listo
  cargarGraficaRecursosPorMarca();
  cargarGraficaUnidadesPorMarca();
  cargarGraficaPrecioPromedioPorMarca();
});

function cargarGraficaRecursosPorMarca() {
  $.ajax({
    url: './backend/stats-recursos-marca.php',
    type: 'GET',
    success: function (response) {
      const data = JSON.parse(response);
      const ctx  = document.getElementById('chartRecursosMarca').getContext('2d');

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Recursos',
            data: data.data
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            title: {
              display: false
            }
          }
        }
      });
    }
  });
}

function cargarGraficaUnidadesPorMarca() {
  $.ajax({
    url: './backend/stats-unidades-marca.php',
    type: 'GET',
    success: function (response) {
      const data = JSON.parse(response);
      const ctx  = document.getElementById('chartUnidadesMarca').getContext('2d');

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Unidades',
            data: data.data
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false }
          }
        }
      });
    }
  });
}

function cargarGraficaPrecioPromedioPorMarca() {
  $.ajax({
    url: './backend/stats-precio-promedio-marca.php',
    type: 'GET',
    success: function (response) {
      const data = JSON.parse(response);
      const ctx  = document.getElementById('chartPrecioMarca').getContext('2d');

      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Precio promedio',
            data: data.data
          }]
        },
        options: {
          responsive: true
        }
      });
    }
  });
}
