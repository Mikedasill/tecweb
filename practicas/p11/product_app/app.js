// product_app/app.js

const $ = (s) => document.querySelector(s);
const resultados = $("#resultados");
const msg = $("#msg");

// --- Buscar ---
$("#frmSearch").addEventListener("submit", async (e) => {
  e.preventDefault();
  resultados.innerHTML = "";
  msg.textContent = "";

  const q = $("#q").value.trim();
  if (!q) { msg.textContent = "Escribe algo para buscar."; return; }

  try {
    const r = await fetch(`./backend/read.php?q=${encodeURIComponent(q)}`);
    const data = await r.json();

    if (!data.ok) {
      resultados.innerHTML = `<article class="card"><b>Error:</b> ${data.error || "Respuesta no válida"}</article>`;
      return;
    }
    if (!data.data || data.data.length === 0) {
      resultados.innerHTML = `<article class="card">Sin coincidencias.</article>`;
      return;
    }

    resultados.innerHTML = data.data.map(p => `
      <article class="card">
        <h3>${p.nombre} <small>(${p.marca} / ${p.modelo})</small></h3>
        <p>${p.descripcion || ""}</p>
        <footer>$${Number(p.precio).toFixed(2)} — Existencias: ${p.existencias}</footer>
      </article>
    `).join("");

  } catch (err) {
    resultados.innerHTML = `<article class="card"><b>Excepción:</b> ${err.message}</article>`;
  }
});

// --- Guardar ---
$("#frmProducto").addEventListener("submit", async (e) => {
  e.preventDefault();
  msg.textContent = "";

  const payload = {
    nombre: $("#nombre").value.trim(),
    modelo: $("#modelo").value.trim(),
    marca: $("#marca").value.trim(),
    precio: $("#precio").value,
    existencias: $("#existencias").value,
    imagen_url: $("#imagen_url").value.trim(),
    detalles: $("#detalles").value.trim()
  };

  // Validaciones básicas (además de HTML5)
  if (payload.nombre.length < 3) { msg.textContent = "Nombre muy corto."; return; }
  if (!/^[A-Za-z0-9_-]{1,25}$/.test(payload.modelo)) { msg.textContent = "Modelo inválido."; return; }
  if (Number(payload.precio) < 100) { msg.textContent = "Precio debe ser ≥ 100."; return; }
  if (Number(payload.existencias) < 0) { msg.textContent = "Existencias debe ser ≥ 0."; return; }

  try {
    const r = await fetch("./backend/create.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });
    const data = await r.json();

    if (!data.ok) {
      msg.innerHTML = `<mark>ERROR:</mark> ${data.error || "Respuesta no válida"}`;
      return;
    }
    msg.innerHTML = `<mark>ÉXITO:</mark> Producto insertado (id=${data.id}).`;

    // Opcional: limpia el formulario
    $("#frmProducto").reset();
    $("#imagen_url").value = "default.png";

  } catch (err) {
    msg.innerHTML = `<mark>Excepción:</mark> ${err.message}`;
  }
});
