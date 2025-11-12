// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
  "precio": 0.0,
  "unidades": 1,
  "modelo": "XX-000",
  "marca": "NA",
  "detalles": "NA",
  "imagen": "img/default.png"
};

$(document).ready(function () {
  let edit = false;

  // Helpers de UI
  function setBaseJSON() {
    const jsonString = JSON.stringify(baseJSON, null, 2);
    $('#description').val(jsonString);
  }
  function showStatus(title, message) {
    let tpl = `
      <li style="list-style:none;"><b>${title}</b></li>
      <li style="list-style:none;">${message}</li>
    `;
    $('#container').html(tpl);
    $('#product-result').show();
  }
  function hideStatus() {
    $('#product-result').hide();
    $('#container').empty();
  }

  // Estado inicial
  setBaseJSON();
  hideStatus();
  listarProductos();

  // =============== LISTAR ==================
  function listarProductos() {
    $.ajax({
      url: './backend/product-list.php',
      type: 'GET',
      success: function (response) {
        let productos = [];
        try {
          productos = JSON.parse(response);
        } catch (e) {
          // si el backend regresó algo no-JSON, no truena la página
          productos = [];
        }

        if (Array.isArray(productos) && productos.length > 0) {
          let template = '';
          productos.forEach(producto => {
            let descripcion = '';
            descripcion += '<li>precio: ' + producto.precio + '</li>';
            descripcion += '<li>unidades: ' + producto.unidades + '</li>';
            descripcion += '<li>modelo: ' + producto.modelo + '</li>';
            descripcion += '<li>marca: ' + producto.marca + '</li>';
            descripcion += '<li>detalles: ' + producto.detalles + '</li>';

            template += `
              <tr productId="${producto.id}">
                <td>${producto.id}</td>
                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                <td><ul>${descripcion}</ul></td>
                <td>
                  <button class="product-delete btn btn-danger">Eliminar</button>
                </td>
              </tr>
            `;
          });
          $('#products').html(template);
        } else {
          $('#products').html('');
        }
      },
      error: function () {
        showStatus('Error', 'No se pudo obtener la lista de productos.');
      }
    });
  }

  // =============== BUSCAR ==================
  $('#search').keyup(function () {
    const q = $('#search').val().trim();
    if (!q) {
      hideStatus();
      return;
    }

    $.ajax({
      url: './backend/product-search.php?search=' + encodeURIComponent(q),
      type: 'GET',
      success: function (response) {
        let productos = [];
        try {
          productos = JSON.parse(response);
        } catch (e) {
          productos = [];
        }

        if (Array.isArray(productos) && productos.length > 0) {
          let template = '';
          let template_bar = '';

          productos.forEach(producto => {
            let descripcion = '';
            descripcion += '<li>precio: ' + producto.precio + '</li>';
            descripcion += '<li>unidades: ' + producto.unidades + '</li>';
            descripcion += '<li>modelo: ' + producto.modelo + '</li>';
            descripcion += '<li>marca: ' + producto.marca + '</li>';
            descripcion += '<li>detalles: ' + producto.detalles + '</li>';

            template += `
              <tr productId="${producto.id}">
                <td>${producto.id}</td>
                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                <td><ul>${descripcion}</ul></td>
                <td>
                  <button class="product-delete btn btn-danger">Eliminar</button>
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
          $('#container').html('<li style="list-style:none;">Sin coincidencias</li>');
          $('#products').html('');
        }
      },
      error: function () {
        showStatus('Error', 'No se pudo realizar la búsqueda.');
      }
    });
  });

  // =============== AGREGAR / EDITAR ==================
  $('#product-form').on('submit', function (e) {
    e.preventDefault();

    // 1) Parsear JSON del textarea con try/catch para "Inserción fallida"
    let postData;
    try {
      postData = JSON.parse($('#description').val());
    } catch (err) {
      showStatus('Inserción fallida', 'JSON inválido.\n' + err.message);
      return;
    }

    // 2) Agregar campos del formulario
    const nombre = $('#name').val().trim();
    const id = $('#productId').val();

    postData['nombre'] = nombre;
    postData['id'] = id;

    // 3) Validaciones mínimas para la práctica
    //    (ajusta a lo que pide tu profe si es distinto)
    const errores = [];
    if (nombre.length < 3) errores.push('El nombre debe tener al menos 3 caracteres.');
    if (isNaN(+postData.precio)) errores.push('precio debe ser numérico.');
    if (+postData.precio < 100) errores.push('El precio debe ser mayor o igual que 100.');
    if (!Number.isInteger(+postData.unidades) || +postData.unidades <= 0) errores.push('unidades debe ser entero positivo.');
    if (!postData.modelo || String(postData.modelo).trim().length === 0) errores.push('modelo es obligatorio.');
    if (!postData.marca || String(postData.marca).trim().length === 0) errores.push('marca es obligatoria.');
    if (!postData.imagen || String(postData.imagen).trim().length === 0) errores.push('imagen es obligatoria.');

    if (errores.length > 0) {
      // Mostrar “Inserción fallida” (para tu captura)
      showStatus('Inserción fallida', errores.join(' '));
      return;
    }

    const url = edit === false ? './backend/product-add.php' : './backend/product-edit.php';

    // 4) Enviar y validar respuesta
    $.post(url, postData)
      .done(function (response) {
        let data;
        try {
          data = JSON.parse(response);
        } catch (e) {
          showStatus('Inserción fallida', 'Respuesta no válida del servidor:\n' + String(response).slice(0, 200));
          return;
        }

        // Compatible con {status:"ok/error", message:"..."} o {ok:true/false, ...}
        const ok = (data.ok === true) || (data.status === 'ok');
        if (!ok) {
          const msg = data.message || data.error || 'Error desconocido';
          showStatus('Inserción fallida', msg);
          return;
        }

        // ÉXITO
        showStatus('ÉXITO', data.message || 'Producto insertado/actualizado');
        $('#name').val('');
        setBaseJSON();
        listarProductos();
        edit = false;
        $('#productId').val(''); // limpia el hidden de edición
      })
      .fail(function () {
        showStatus('Inserción fallida', 'No se pudo contactar al servidor.');
      });
  });

  // =============== ELIMINAR ==================
  // Usa function() para que "this" sea el botón clicado
  $(document).on('click', '.product-delete', function (e) {
    e.preventDefault();
    if (!confirm('¿Realmente deseas eliminar el producto?')) return;

    const row = $(this).closest('tr');
    const id = row.attr('productId');

    $.post('./backend/product-delete.php', { id })
      .done(function () {
        hideStatus();
        listarProductos();
      })
      .fail(function () {
        showStatus('Error', 'No se pudo eliminar el producto.');
      });
  });

  // =============== CARGAR PARA EDITAR ==================
  $(document).on('click', '.product-item', function (e) {
    e.preventDefault();
    const row = $(this).closest('tr');
    const id = row.attr('productId');

    $.post('./backend/product-single.php', { id }, function (response) {
      let product;
      try {
        product = JSON.parse(response);
      } catch (e) {
        showStatus('Error', 'Respuesta inválida al obtener el producto.');
        return;
      }

      $('#name').val(product.nombre);
      $('#productId').val(product.id);

      // limpiamos campos que no van en el JSON editable
      delete product.nombre;
      delete product.eliminado;
      delete product.id;

      const jsonString = JSON.stringify(product, null, 2);
      $('#description').val(jsonString);

      edit = true;
    }).fail(function () {
      showStatus('Error', 'No se pudo obtener el producto.');
    });
  });
});
