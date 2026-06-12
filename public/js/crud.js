// ═══════════════════════════════════════════
//  CRUD.JS · AUTA · Conexión con API Laravel
// ═══════════════════════════════════════════

const API = {
    asistencia: "/api/asistencia",
};

function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]')?.content || "";
}

async function cargarAsistencia() {
    try {
        const res = await fetch(API.asistencia);
        const data = await res.json();
        renderDashboard(data);
        renderTablaRegistro(data);
    } catch (e) {
        console.error("Error cargando asistencia:", e);
    }
}

function renderDashboard(data) {
    const hoy = new Date().toISOString().split("T")[0];
    const hoyData = data.filter((a) => a.fecha === hoy);

    const kTotal = document.getElementById("k-total");
    const kPres = document.getElementById("k-pres");
    const kAus = document.getElementById("k-aus");
    const kRet = document.getElementById("k-ret");

    if (kTotal) kTotal.textContent = hoyData.length;
    if (kPres)
        kPres.textContent = hoyData.filter(
            (a) => a.estado === "Presente",
        ).length;
    if (kAus)
        kAus.textContent = hoyData.filter((a) => a.estado === "Ausente").length;
    if (kRet)
        kRet.textContent = hoyData.filter((a) => a.estado === "Retardo").length;

    const tbody = document.getElementById("dash-tbody");
    if (!tbody) return;
    tbody.innerHTML = "";
    const recientes = data.slice(0, 5);
    if (recientes.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" style="padding:1rem;color:var(--muted);text-align:center">Sin registros. Crea el primero</td></tr>`;
        return;
    }
    recientes.forEach((a) => {
        const badge =
            a.estado === "Presente"
                ? "gr"
                : a.estado === "Retardo"
                  ? "or"
                  : "re";
        tbody.innerHTML += `<tr>
      <td>${a.nombre}</td><td>${a.grado}</td><td>${a.fecha}</td>
      <td>${a.hora}</td><td><span class="st-badge ${badge}">${a.estado}</span></td>
    </tr>`;
    });
}

let filtros = { grado: "Todos", estado: "Todos" };
let todaAsistencia = [];

function renderTablaRegistro(data) {
    todaAsistencia = data;
    aplicarFiltros();
}

function aplicarFiltros() {
    const tbody = document.getElementById("reg-tbody");
    if (!tbody) return;
    tbody.innerHTML = "";
    let filtrada = todaAsistencia;
    if (filtros.grado !== "Todos")
        filtrada = filtrada.filter((a) => a.grado === filtros.grado);
    if (filtros.estado !== "Todos")
        filtrada = filtrada.filter((a) => a.estado === filtros.estado);
    if (filtrada.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" style="padding:1rem;color:var(--muted);text-align:center">Sin registros con esos filtros.</td></tr>`;
        return;
    }
    filtrada.forEach((a) => {
        const badge =
            a.estado === "Presente"
                ? "gr"
                : a.estado === "Retardo"
                  ? "or"
                  : "re";
        tbody.innerHTML += `<tr>
      <td>${a.id}</td><td>${a.nombre}</td><td>${a.grado}</td>
      <td>${a.fecha}</td><td>${a.hora}</td>
      <td><span class="st-badge ${badge}">${a.estado}</span></td>
      <td><button class="btn-icon re" onclick="eliminarRegistro(${a.id})"><i class="fas fa-trash"></i></button></td>
    </tr>`;
    });
}

function setFilt(campo, valor, lblId) {
    filtros[campo] = valor;
    const lbl = document.getElementById(lblId);
    if (lbl) lbl.textContent = valor;
    aplicarFiltros();
}

async function addRec() {
    const nombre = document.getElementById("f-nombre")?.value.trim();
    const grado = document.getElementById("f-grado")?.value;
    const estado = document.getElementById("f-estado")?.value;
    const fecha = document.getElementById("f-fecha")?.value;
    const hora = document.getElementById("f-hora")?.value;
    const err = document.getElementById("f-err");

    if (!nombre || !grado || !estado || !fecha || !hora) {
        if (err) {
            err.textContent = "⚠ Completa todos los campos.";
            err.style.display = "block";
        }
        return;
    }
    if (err) err.style.display = "none";

    try {
        const res = await fetch(API.asistencia, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": getCsrf(),
            },
            body: JSON.stringify({ nombre, grado, estado, fecha, hora }),
        });
        if (res.ok) {
            document.getElementById("f-nombre").value = "";
            document.getElementById("f-grado").value = "";
            document.getElementById("f-estado").value = "";
            document.getElementById("f-grado-lbl").textContent =
                "Seleccionar grado";
            document.getElementById("f-estado-lbl").textContent =
                "Seleccionar estado";
            cargarAsistencia();
        } else {
            if (err) {
                err.textContent = "⚠ Error al guardar.";
                err.style.display = "block";
            }
        }
    } catch (e) {
        console.error(e);
    }
}

async function eliminarRegistro(id) {
    if (!confirm("¿Eliminar este registro?")) return;
    try {
        const res = await fetch(`${API.asistencia}/${id}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": getCsrf() },
        });
        if (res.ok) cargarAsistencia();
    } catch (e) {
        console.error(e);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const hoy = new Date();
    const fFecha = document.getElementById("f-fecha");
    const fHora = document.getElementById("f-hora");
    if (fFecha) fFecha.value = hoy.toISOString().split("T")[0];
    if (fHora) fHora.value = hoy.toTimeString().slice(0, 5);
    cargarAsistencia();
});

// Recargar datos cada 30 segundos
setInterval(() => {
    cargarAsistencia();
}, 30000);
