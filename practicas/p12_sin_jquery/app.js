/* ============================
   ProductApp SIN jQuery - MODO DIAGNÓSTICO
   ============================ */

const BASE_JSON = {
  precio: 0,
  unidades: 1,
  modelo: "XX-000",
  marca: "NA",
  detalles: "NA",
  imagen: "img/default.png"
};

let isEditing = false;

function $(sel){ return document.querySelector(sel); }

function need(el, name){
  if(!el){ throw new Error(`Falta el elemento ${name} en el HTML`); }
  return el;
}

function ensureStatus(){
  let el = $('#status');
  if(!el){
    el = document.createElement('pre'); // pre para ver crudos
    el.id = 'status';
    el.style.whiteSpace = 'pre-wrap';
    el.style.marginTop = '10px';
    el.style.padding = '10px';
    el.style.borderRadius = '6px';
    el.style.background = '#112B3C';
    el.style.color = '#E8F0FE';
    // lo ponemos al final del body si no hay contenedor claro
    (document.body).appendChild(el);
  }
  return el;
}

function showOK(msg){
  const el = ensureStatus();
  el.style.border = '1px solid #19c37d';
  el.textContent = `OK:\n${msg}`;
}
function showERR(msg){
  const el = ensureStatus();
  el.style.border = '1px solid #ef4444';
  el.textContent = `ERROR:\n${msg}`;
}
function showRAW(title, raw){
  const el = ensureStatus();
  el.style.border = '1px solid #3b82f6';
  el.textContent = `${title} (raw):\n${raw}`;
}

function setDefaults(){
  const name = $('#name');
  const json = $('#json');
  if(name) name.value = '';
  if(json) json.value = JSON.stringify(BASE_JSON, null, 2);
  const hid = ensureHiddenId();
  hid.value = '';
  isEditing = false;
  const btn = $('#btn-save');
  if(btn) btn.textContent = 'Agregar / Guardar';
}

function ensureHiddenId(){
  let hid = $('#productId');
  if(!hid){
    hid = document.createElement('input');
    hid.type = 'hidden';
    hid.id  = 'productId';
    document.body.appendChild(hid);
  }
  return hid;
}

function buildDescList(p){
  return `
    <ul class="mb-0">
      <li>precio: ${p.precio}</li>
      <li>unidades: ${p.unidades}</li>
      <li>modelo: ${p.modelo}</li>
      <li>marca: ${p.marca}</li>
      <li>detalles: ${p.detalles}</li>
    </ul>
  `;
}

function renderProducts(arr){
  const tbody = need($('#products'), '#products');
  if(!Array.isArray(arr) || arr.length===0){ tbody.innerHTML=''; return; }
  let rows = '';
  for(const p of arr){
    rows += `
      <tr data-id="${p.id}">
        <td>${p.id}</td>
        <td><a href="#" class="product-item">${p.nombre}</a></td>
        <td>${buildDescList(p)}</td>
        <td><button type="button" class="btn btn-danger btn-sm btn-delete">Eliminar</button></td>
      </tr>`;
  }
  tbody.innerHTML = rows;
}

async function fetchGET(url){
  console.log('[GET]', url);
  const res = await fetch(url, {cache:'no-store'});
  const raw = await res.text();
  console.log('RAW GET:', raw);
  try{
    const json = JSON.parse(raw);
    return { ok:true, json, raw };
  }catch(e){
    return { ok:false, raw };
  }
}

async function fetchPOST(url, data){
  console.log('[POST]', url, data);
  const fd = new FormData();
  Object.entries(data).forEach(([k,v])=>fd.append(k, v));
  const res = await fetch(url, { method:'POST', body:fd });
  const raw = await res.text();
  console.log('RAW POST:', raw);
  try{
    const json = JSON.parse(raw);
    return { ok:true, json, raw };
  }catch(e){
    return { ok:false, raw };
  }
}

async function listar(){
  const r = await fetchGET('./backend/product-list.php');
  if(r.ok){
    renderProducts(r.json);
    showOK('Listado OK (ver consola si necesitas).');
  }else{
    showRAW('Listado devolvió algo no JSON', r.raw);
  }
}

let searchTimer=null;
function onSearchKey(){
  const q = ($('#search')?.value || '').trim();
  clearTimeout(searchTimer);
  searchTimer = setTimeout(async ()=>{
    if(!q){ await listar(); return; }
    const r = await fetchGET('./backend/product-search.php?search='+encodeURIComponent(q));
    if(r.ok){
      renderProducts(r.json);
      showOK('Búsqueda OK.');
    }else{
      showRAW('Búsqueda NO JSON', r.raw);
    }
  }, 250);
}

async function save(ev){
  if(ev) ev.preventDefault(); // por si es submit
  const name = $('#name')?.value?.trim();
  const jsonTxt = $('#json')?.value || '';
  if(!name || name.length<3){ return showERR('Nombre mínimo 3 caracteres.'); }
  let payload;
  try{ payload = JSON.parse(jsonTxt); }
  catch{ return showERR('JSON inválido.'); }
  if (typeof payload.precio!=='number'){ return showERR('"precio" debe ser número.'); }
  if (!Number.isInteger(payload.unidades)){ return showERR('"unidades" debe ser entero.'); }
  payload.nombre = name;

  const hid = ensureHiddenId();
  const id = hid.value ? parseInt(hid.value,10) : null;
  if(id) payload.id = id;

  const url = (id && isEditing) ? './backend/product-edit.php' : './backend/product-add.php';
  const r = await fetchPOST(url, payload);
  if(r.ok){
    const out = r.json; // {status,message,...}
    if(out.status==='ok'){
      showOK(out.message || 'Operación exitosa.');
      setDefaults();
      await listar();
    }else{
      showERR(out.message || 'Operación reportó error.');
    }
  }else{
    showRAW('POST NO JSON', r.raw);
  }
}

async function onDelete(row){
  const id = row.getAttribute('data-id');
  if(!id) return;
  if(!confirm('¿Eliminar producto?')) return;
  const r = await fetchPOST('./backend/product-delete.php', { id });
  if(r.ok){
    const out = r.json;
    if(out.status==='ok'){
      showOK(out.message || 'Eliminado.');
      await listar();
    }else{
      showERR(out.message || 'Error al eliminar.');
    }
  }else{
    showRAW('DELETE NO JSON', r.raw);
  }
}

async function onEdit(row, ev){
  ev.preventDefault();
  const id = row.getAttribute('data-id');
  if(!id) return;
  const r = await fetchPOST('./backend/product-single.php', { id });
  if(r.ok){
    const p = r.json;
    $('#name').value = p.nombre || '';
    ensureHiddenId().value = p.id || '';
    const copy = {...p}; delete copy.id; delete copy.eliminado; delete copy.nombre;
    $('#json').value = JSON.stringify(copy, null, 2);
    isEditing = true;
    const btn = $('#btn-save'); if(btn) btn.textContent = 'Guardar cambios';
    showOK('Cargado para edición.');
  }else{
    showRAW('SINGLE NO JSON', r.raw);
  }
}

document.addEventListener('DOMContentLoaded', async ()=>{
  // Verificamos elementos clave
  try {
    need($('#products'), '#products');
    need($('#json'), '#json');
    need($('#name'), '#name');
  } catch(e){
    return showERR(e.message);
  }

  // valores por defecto
  setDefaults();

  // listar inicial
  await listar();

  // search
  const s = $('#search'); if(s) s.addEventListener('keyup', onSearchKey);

  // botón guardar (si existe)
  const btn = $('#btn-save'); if(btn) btn.addEventListener('click', save);

  // form submit (por si tu botón es submit)
  const form = $('form'); if(form) form.addEventListener('submit', save);

  // delegación en la tabla
  const tbody = $('#products');
  tbody.addEventListener('click', (e)=>{
    const t = e.target;
    if(t.classList.contains('btn-delete')){
      const row = t.closest('tr'); onDelete(row);
    }else if(t.classList.contains('product-item')){
      const row = t.closest('tr'); onEdit(row, e);
    }
  });

  // Mensaje visible de arranque
  showOK('App cargada. Si algo falla, revisa también la consola (F12).');
});
