<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel · AUTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}" />
</head>
<body>
    <div class="app-page active" id="page-panel">
        <!-- Sidebar fijo del panel -->
        <aside class="panel-sb">
            <div class="psb-logo">
                <div class="psb-logo-ic"><i class="fas fa-qrcode"></i></div>
                <div class="psb-logo-txt">
                    <h6>Panel de Control</h6>
                    <small>{{ config('institucion.nombre_corto') }}</small>
                </div>
            </div>
            <nav class="psb-nav" id="psb-nav">
                <!-- El contenido del menú se carga con JavaScript según el rol -->
            </nav>
            <div class="psb-footer">
                <div class="user-chip">
                    <div class="uc-av"><i class="fas fa-user"></i></div>
                    <div class="uc-info">
                        <div class="uname" id="sbUser">—</div>
                        <div class="urole" id="sbRole">—</div>
                    </div>
                </div>
                <button class="logout-btn" onclick="doLogout()">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                </button>
            </div>
        </aside>

        <!-- Panel main -->
        <div class="panel-main">
            <div class="panel-topbar">
                <h5 id="pTopTitle">Dashboard</h5>
                <div class="p-pill">
                    <i class="fas fa-circle" style="font-size: 0.5rem"></i> Sistema activo
                </div>
            </div>

            <div class="panel-content" id="panel-content">
                <div id="contenido-dinamico"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ======================================================
        // 1.RECUPERAR SESIÓN
        // ======================================================
        const _user = sessionStorage.getItem("auta_user") || "admin";
        const _roleLabel = sessionStorage.getItem("auta_role") || "Administrador";
        const _roleKey = (() => {
            if (_roleLabel === "Administrador") return "admin";
            if (_roleLabel === "Docente") return "docente";
            if (_roleLabel === "Estudiante") return "estudiante";
            return "admin";
        })();

        const sbUserEl = document.getElementById("sbUser");
        const sbRoleEl = document.getElementById("sbRole");
        if (sbUserEl) sbUserEl.textContent = _user;
        if (sbRoleEl) sbRoleEl.textContent = _roleLabel;

        // ======================================================
        // 2. CONFIGURAR MENÚ SEGÚN EL ROL
        // ======================================================
        function cargarMenu() {
            const nav = document.getElementById("psb-nav");
            if (!nav) return;

            if (_roleKey === "estudiante") {
                nav.innerHTML = `
                    <div class="psb-lbl">Principal</div>
                    <button class="psb-btn active" onclick="mostrarVista('miAsistencia')">
                        <span class="psb-ic"><i class="fas fa-calendar-check"></i></span>
                        Mi Asistencia
                    </button>
                    <div class="psb-lbl">Navegación</div>
                    <button class="psb-btn" onclick="window.location.href='/'">
                        <span class="psb-ic"><i class="fas fa-globe"></i></span>Ver Sitio Web
                    </button>
                `;
            } else if (_roleKey === "docente") {
                nav.innerHTML = `
                    <div class="psb-lbl">Principal</div>
                    <button class="psb-btn active" onclick="mostrarVista('reportesDocente')">
                        <span class="psb-ic"><i class="fas fa-chart-bar"></i></span>
                        Reportes de Asistencia
                    </button>
                    <div class="psb-lbl">Navegación</div>
                    <button class="psb-btn" onclick="window.location.href='/'">
                        <span class="psb-ic"><i class="fas fa-globe"></i></span>Ver Sitio Web
                    </button>
                `;
            } else {
                // Admin
                nav.innerHTML = `
                    <div class="psb-lbl">Principal</div>
                    <button class="psb-btn active" onclick="mostrarVista('dashboard')">
                        <span class="psb-ic"><i class="fas fa-tachometer-alt"></i></span>
                        Dashboard
                    </button>
                    <button class="psb-btn" onclick="mostrarVista('registro')">
                        <span class="psb-ic"><i class="fas fa-clipboard-list"></i></span>
                        Registrar Asistencia
                    </button>
                    <button class="psb-btn" onclick="mostrarVista('reportes')">
                        <span class="psb-ic"><i class="fas fa-chart-bar"></i></span>
                        Reportes
                    </button>
                    <div class="psb-lbl">Administración</div>
                    <button class="psb-btn" onclick="mostrarVista('grados')">
                        <span class="psb-ic"><i class="fas fa-layer-group"></i></span>
                        Grados
                    </button>
                    <button class="psb-btn" onclick="mostrarVista('grupos')">
                        <span class="psb-ic"><i class="fas fa-users"></i></span>
                        Grupos
                    </button>
                    <button class="psb-btn" onclick="mostrarVista('horarios')">
                        <span class="psb-ic"><i class="fas fa-clock"></i></span>
                        Horarios
                    </button>
                    <div class="psb-lbl">Navegación</div>
                    <button class="psb-btn" onclick="window.location.href='/escaner'">
                        <span class="psb-ic"><i class="fas fa-qrcode"></i></span>
                        Escáner QR
                    </button>
                    <button class="psb-btn" onclick="window.location.href='/carnets'">
                        <span class="psb-ic"><i class="fas fa-id-card"></i></span>
                        Generar Carnets QR
                    </button>
                    <button class="psb-btn" onclick="window.location.href='/'">
                        <span class="psb-ic"><i class="fas fa-globe"></i></span>Ver Sitio Web
                    </button>
                `;
            }
        }

        // ======================================================
        // 3. MOSTRAR VISTA SEGÚN EL ROL Y OPCIÓN SELECCIONADA
        // ======================================================
        function mostrarVista(vista) {
            // Cambiar botón activo
            document.querySelectorAll(".psb-btn").forEach(btn => btn.classList.remove("active"));
            if (event && event.target) {
                const btn = event.target.closest?.(".psb-btn");
                if (btn) btn.classList.add("active");
            }

            const contenedor = document.getElementById("contenido-dinamico");
            if (!contenedor) return;

            if (_roleKey === "estudiante") {
                if (vista === "miAsistencia") {
                    document.getElementById("pTopTitle").textContent = "Mi Asistencia";
                    mostrarMiAsistencia();
                }
            } 
            else if (_roleKey === "docente") {
                if (vista === "reportesDocente") {
                    document.getElementById("pTopTitle").textContent = "Reportes de Asistencia";
                    mostrarReportesDocente();
                }
            } 
            else {
                // Admin
                if (vista === "dashboard") {
                    document.getElementById("pTopTitle").textContent = "Dashboard";
                    cargarDashboardAdmin();
                } else if (vista === "registro") {
                    document.getElementById("pTopTitle").textContent = "Registrar Asistencia";
                    cargarRegistroAdmin();
                } else if (vista === "reportes") {
                    document.getElementById("pTopTitle").textContent = "Reportes";
                    cargarReportesAdmin();
                } else if (vista === "grados") {
                    document.getElementById("pTopTitle").textContent = "Grados";
                    cargarGradosAdmin();
                } else if (vista === "grupos") {
                    document.getElementById("pTopTitle").textContent = "Grupos";
                    cargarGruposAdmin();
                } else if (vista === "horarios") {
                    document.getElementById("pTopTitle").textContent = "Horarios";
                    cargarHorariosAdmin();
                }
            }
        }

        // ======================================================
        // 4. VISTA ESTUDIANTE: VER SU PROPIA ASISTENCIA
        // ======================================================
        async function mostrarMiAsistencia() {
            const html = `
                <div class="p-card">
                    <div class="p-card-hd">
                        <h6><i class="fas fa-calendar-check" style="margin-right: 0.4rem; color: var(--blue-light);"></i>Mi Historial de Asistencia</h6>
                    </div>
                    <div class="tbl-wrap">
                        <table class="rtbl">
                            <thead>
                                <tr><th>Fecha</th><th>Hora</th><th>Estado</th><th>Grado</th></tr>
                            </thead>
                            <tbody id="estudiante-tbody"></tbody>
                         </table>
                    </div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;

            try {
                // El servidor ya filtra esto: si quien está autenticado es un
                // estudiante, /api/asistencia solo devuelve SUS registros.
                // (Antes se pedían todos y se filtraban aquí por nombre, lo
                // cual además de no funcionar bien, exponía la asistencia de
                // todo el colegio a cualquier cuenta de estudiante.)
                const res = await fetch("/api/asistencia");
                const misAsistencias = await res.json();
                const tbody = document.getElementById("estudiante-tbody");

                if (misAsistencias.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="4"><div class="empty-st">No tienes registros de asistencia aún.</div></td></tr>`;
                    return;
                }
                
                tbody.innerHTML = misAsistencias.map(a => `
                    <tr>
                        <td>${a.fecha}</td>
                        <td>${a.hora}</td>
                        <td><span class="badge ${a.estado === 'Presente' ? 'presente' : a.estado === 'Retardo' ? 'retardo' : 'ausente'}">${a.estado}</span></td>
                        <td>${a.grado}</td>
                    </tr>
                `).join('');
            } catch(e) {
                console.error(e);
            }
        }

        // ======================================================
        // 5. VISTA DOCENTE: REPORTES (SOLO LECTURA)
        // ======================================================
        async function mostrarReportesDocente() {
            const html = `
                <div class="p-card">
                    <div class="p-card-hd">
                        <h6><i class="fas fa-chart-bar" style="margin-right: 0.4rem; color: var(--blue-light);"></i>Reportes de Asistencia</h6>
                    </div>
                    <div class="p-card-bd" style="padding-top:0;padding-bottom:0.75rem;">
                        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:0.75rem;align-items:center;">
                            <input type="text" class="pinp" id="rep-doc-buscar" placeholder="🔎 Buscar por nombre..." style="flex:1;min-width:220px;" oninput="resetPagina('repDoc');renderReportesDocenteFiltrado()" />
                            <select class="pinp" id="rep-doc-filtro-grado" style="min-width:180px;background:rgba(255,255,255,0.05)" onchange="resetPagina('repDoc');renderReportesDocenteFiltrado()">
                                <option value="">Todos los grados</option>
                            </select>
                            <input type="date" class="pinp" id="rep-doc-filtro-fecha" title="Filtrar por fecha" style="min-width:160px;background:rgba(255,255,255,0.05)" onchange="resetPagina('repDoc');renderReportesDocenteFiltrado()" />
                            <button class="act-btn" title="Limpiar filtros" onclick="limpiarFiltros('repDoc', renderReportesDocenteFiltrado)"><i class="fas fa-times"></i> Limpiar</button>
                        </div>
                    </div>
                    <div class="tbl-wrap">
                        <table class="rtbl">
                            <thead>
                                <tr>${thOrdenable('repDoc','nombre','Estudiante','renderReportesDocenteFiltrado')}${thOrdenable('repDoc','grado','Grado','renderReportesDocenteFiltrado')}${thOrdenable('repDoc','fecha','Fecha','renderReportesDocenteFiltrado')}${thOrdenable('repDoc','hora','Hora','renderReportesDocenteFiltrado')}${thOrdenable('repDoc','estado','Estado','renderReportesDocenteFiltrado')}</tr>
                            </thead>
                            <tbody id="reportes-tbody"></tbody>
                        </table>
                    </div>
                    <div id="repDoc-paginacion" style="padding:0 1rem;"></div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;

            try {
                const res = await fetch("/api/asistencia");
                _repDocenteData = await res.json();
                poblarFiltroGrados("rep-doc-filtro-grado", _repDocenteData);
                renderReportesDocenteFiltrado();
            } catch(e) {
                console.error(e);
            }
        }

        let _repDocenteData = [];

        function renderReportesDocenteFiltrado() {
            const tbody = document.getElementById("reportes-tbody");
            if (!tbody) return;

            const busqueda = normalizarTexto(document.getElementById("rep-doc-buscar")?.value);
            const grado = document.getElementById("rep-doc-filtro-grado")?.value || "";
            const fecha = document.getElementById("rep-doc-filtro-fecha")?.value || "";

            const dataFiltrada = ordenarDatos("repDoc", _repDocenteData.filter(a => {
                const coincideBusqueda = !busqueda || normalizarTexto(a.nombre).includes(busqueda);
                const coincideGrado = !grado || a.grado === grado;
                const coincideFecha = !fecha || a.fecha === fecha;
                return coincideBusqueda && coincideGrado && coincideFecha;
            }));

            const info = paginarDatos("repDoc", dataFiltrada);
            renderControlesPaginacion("repDoc", info, "renderReportesDocenteFiltrado");

            if (info.pageData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5"><div class="empty-st">No hay registros que coincidan con la búsqueda.</div></td></tr>`;
                return;
            }
            tbody.innerHTML = info.pageData.map(a => `
                <tr>
                    <td>${a.nombre}</td>
                    <td>${a.grado}</td>
                    <td>${a.fecha}</td>
                    <td>${a.hora}</td>
                    <td><span class="badge ${a.estado === 'Presente' ? 'presente' : a.estado === 'Retardo' ? 'retardo' : 'ausente'}">${a.estado}</span></td>
                </tr>
            `).join('');
        }

        // ======================================================
        // 6. VISTAS ADMIN
        // ======================================================
        async function cargarDashboardAdmin() {
            const html = `
                <div class="kpi-grid">
                    <div class="kpi-card"><div class="kpi-ic bl"><i class="fas fa-users"></i></div><div><div class="kpi-val" id="k-total">0</div><div class="kpi-lbl">Total registros</div></div></div>
                    <div class="kpi-card"><div class="kpi-ic gr"><i class="fas fa-check-circle"></i></div><div><div class="kpi-val" id="k-pres">0</div><div class="kpi-lbl">Presentes</div></div></div>
                    <div class="kpi-card"><div class="kpi-ic re"><i class="fas fa-times-circle"></i></div><div><div class="kpi-val" id="k-aus">0</div><div class="kpi-lbl">Ausentes</div></div></div>
                    <div class="kpi-card"><div class="kpi-ic or"><i class="fas fa-clock"></i></div><div><div class="kpi-val" id="k-ret">0</div><div class="kpi-lbl">Retardos</div></div></div>
                </div>
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-history" style="margin-right: 0.4rem; color: var(--blue-light);"></i>Registros recientes</h6></div>
                    <div class="tbl-wrap"><table class="rtbl"><thead><tr><th>Estudiante</th><th>Grado</th><th>Fecha</th><th>Hora</th><th>Estado</th></tr></thead><tbody id="dash-tbody"></tbody></table></div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;
            await actualizarDashboardAdmin();
        }

        async function actualizarDashboardAdmin() {
            try {
                const res = await fetch("/api/asistencia");
                const data = await res.json();
                const hoy = new Date().toISOString().split("T")[0];
                const hoyData = data.filter(a => a.fecha === hoy);
                document.getElementById("k-total").textContent = hoyData.length;
                document.getElementById("k-pres").textContent = hoyData.filter(a => a.estado === "Presente").length;
                document.getElementById("k-aus").textContent = hoyData.filter(a => a.estado === "Ausente").length;
                document.getElementById("k-ret").textContent = hoyData.filter(a => a.estado === "Retardo").length;
                const tbody = document.getElementById("dash-tbody");
                if (tbody) {
                    tbody.innerHTML = data.slice(0, 8).map(a => `
                        <tr><td>${a.nombre}</td><td>${a.grado}</td><td>${a.fecha}</td><td>${a.hora}</td><td><span class="badge ${a.estado === 'Presente' ? 'presente' : a.estado === 'Retardo' ? 'retardo' : 'ausente'}">${a.estado}</span></td></tr>
                    `).join('');
                }
            } catch(e) { console.error(e); }
        }

        function cargarRegistroAdmin() {
            const html = `
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-plus-circle" style="margin-right: 0.4rem; color: var(--accent2);"></i>Nuevo Registro</h6></div>
                    <div class="p-card-bd">
                        <div class="form-grid">
                            <div><label class="flbl2">Estudiante</label>
                                <select class="pinp" id="f-estudiante" onchange="actualizarGradoSeleccionado()" style="background:rgba(255, 255, 255, 0.05)">
                                    <option value="">Cargando estudiantes…</option>
                                </select>
                            </div>
                            <div><label class="flbl2">Grado</label><input type="text" class="pinp" id="f-grado" placeholder="Se autocompleta" readonly /></div>
                            <div><label class="flbl2">Estado</label>
                                <select class="pinp" id="f-estado" style="background:rgba(255, 255, 255, 0.05)">
                                    <option value="Presente">Presente</option>
                                    <option value="Ausente">Ausente</option>
                                    <option value="Retardo">Retardo</option>
                                </select>
                            </div>
                            <div><label class="flbl2">Fecha</label><input type="date" class="pinp" id="f-fecha" /></div>
                            <div><label class="flbl2">Hora</label><input type="time" class="pinp" id="f-hora" /></div>
                            <div style="display: flex; align-items: flex-end"><button class="btn-add" onclick="agregarRegistroAdmin()"><i class="fas fa-plus"></i> Registrar</button></div>
                        </div>
                        <div id="f-err" style="display:none;color:#ff8080;margin-top:0.5rem;font-size:0.85rem;"></div>
                    </div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;
            const hoy = new Date();
            document.getElementById("f-fecha").value = hoy.toISOString().split("T")[0];
            document.getElementById("f-hora").value = hoy.toTimeString().slice(0, 5);
            cargarEstudiantesSelect();
        }

        async function cargarEstudiantesSelect() {
            const select = document.getElementById("f-estudiante");
            if (!select) return;
            try {
                const res = await fetch("/api/estudiantes");
                const estudiantes = await res.json();
                if (!estudiantes.length) {
                    select.innerHTML = `<option value="">No hay estudiantes registrados</option>`;
                    return;
                }
                select.innerHTML = `<option value="">Selecciona un estudiante</option>` +
                    estudiantes.map(e =>
                        `<option value="${e.id}" data-nombre="${(e.nombre || "").replace(/"/g,'&quot;')}" data-grado="${(e.grado || "").replace(/"/g,'&quot;')}">${e.nombre} — ${e.grado} (${e.codigo})</option>`
                    ).join("");
            } catch (e) {
                console.error(e);
                select.innerHTML = `<option value="">Error al cargar estudiantes</option>`;
            }
        }

        function actualizarGradoSeleccionado() {
            const select = document.getElementById("f-estudiante");
            const gradoInput = document.getElementById("f-grado");
            if (!select || !gradoInput) return;
            const opt = select.selectedOptions[0];
            gradoInput.value = opt ? (opt.dataset.grado || "") : "";
        }

        // Quita tildes/mayúsculas para que la búsqueda sea más tolerante
        // (ej: "jose" encuentra "José", "GARCIA" encuentra "García")
        function normalizarTexto(txt) {
            return (txt || "").toString()
                .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                .toLowerCase().trim();
        }

        // ---------- Notificaciones flotantes (toast) ----------
        // Reemplaza a alert() para no interrumpir al usuario con un modal nativo.
        function mostrarToast(mensaje, tipo = "success") {
            let cont = document.getElementById("toast-panel");
            if (!cont) {
                cont = document.createElement("div");
                cont.id = "toast-panel";
                cont.style.cssText = "position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:0.5rem;";
                document.body.appendChild(cont);
            }
            const colores = {
                success: { bg: "#1f3d2c", border: "#3ecf8e", text: "#8CF5B9" },
                error:   { bg: "#3d1f1f", border: "#e05555", text: "#ff9a9a" },
            };
            const c = colores[tipo] || colores.success;
            const toast = document.createElement("div");
            toast.textContent = (tipo === "success" ? "✓ " : "⚠ ") + mensaje;
            toast.style.cssText = `background:${c.bg};border:1px solid ${c.border};color:${c.text};padding:0.7rem 1.1rem;border-radius:8px;font-size:0.9rem;box-shadow:0 4px 14px rgba(0,0,0,0.35);opacity:0;transform:translateY(8px);transition:opacity .25s ease, transform .25s ease;max-width:320px;`;
            cont.appendChild(toast);
            requestAnimationFrame(() => { toast.style.opacity = "1"; toast.style.transform = "translateY(0)"; });
            setTimeout(() => {
                toast.style.opacity = "0";
                toast.style.transform = "translateY(8px)";
                setTimeout(() => toast.remove(), 300);
            }, 2800);
        }

        // ---------- Ordenamiento de columnas (clic en encabezado) ----------
        // _sortState guarda, por tabla (prefix), qué columna está activa y en
        // qué dirección (1 = ascendente, -1 = descendente).
        let _sortState = {};

        function ordenarColumna(prefix, columna, renderFn) {
            if (!_sortState[prefix]) _sortState[prefix] = { col: null, dir: 1 };
            const estado = _sortState[prefix];
            if (estado.col === columna) {
                estado.dir *= -1;
            } else {
                estado.col = columna;
                estado.dir = 1;
            }
            actualizarIndicadoresOrden(prefix, estado);
            renderFn();
        }

        function ordenarDatos(prefix, data) {
            const estado = _sortState[prefix];
            if (!estado || !estado.col) return data;
            const copia = [...data];
            copia.sort((a, b) => {
                let va = a[estado.col], vb = b[estado.col];
                if (estado.col === "id") {
                    va = Number(va); vb = Number(vb);
                } else {
                    va = normalizarTexto(va); vb = normalizarTexto(vb);
                }
                if (va < vb) return -1 * estado.dir;
                if (va > vb) return 1 * estado.dir;
                return 0;
            });
            return copia;
        }

        function actualizarIndicadoresOrden(prefix, estado) {
            document.querySelectorAll(`[id^="${prefix}-ind-"]`).forEach(el => el.textContent = "");
            if (estado.col) {
                const el = document.getElementById(`${prefix}-ind-${estado.col}`);
                if (el) el.textContent = estado.dir === 1 ? " ▲" : " ▼";
            }
        }

        // Encabezado clicable reutilizable: <th onclick=ordenarColumna(...)>Texto <span indicador></span></th>
        function thOrdenable(prefix, columna, texto, renderFnNombre) {
            return `<th onclick="ordenarColumna('${prefix}','${columna}',${renderFnNombre})" style="cursor:pointer;user-select:none;" title="Ordenar por ${texto}">${texto}<span id="${prefix}-ind-${columna}"></span></th>`;
        }

        // ---------- Paginación (client-side, sobre los datos ya filtrados/ordenados) ----------
        const FILAS_POR_PAGINA = 15;
        let _pageState = {};

        function paginarDatos(prefix, data) {
            if (!_pageState[prefix]) _pageState[prefix] = 1;
            const totalPaginas = Math.max(1, Math.ceil(data.length / FILAS_POR_PAGINA));
            if (_pageState[prefix] > totalPaginas) _pageState[prefix] = totalPaginas;
            if (_pageState[prefix] < 1) _pageState[prefix] = 1;
            const inicio = (_pageState[prefix] - 1) * FILAS_POR_PAGINA;
            return {
                pageData: data.slice(inicio, inicio + FILAS_POR_PAGINA),
                totalPaginas,
                paginaActual: _pageState[prefix],
                total: data.length,
            };
        }

        function cambiarPagina(prefix, delta, renderFn) {
            _pageState[prefix] = (_pageState[prefix] || 1) + delta;
            renderFn();
        }

        // Vuelve a la página 1 (se llama al cambiar la búsqueda o el filtro de grado,
        // para no quedar "atrapado" en una página que ya no tiene resultados)
        function resetPagina(prefix) {
            _pageState[prefix] = 1;
        }

        function renderControlesPaginacion(prefix, info, renderFnNombre) {
            const contenedor = document.getElementById(`${prefix}-paginacion`);
            if (!contenedor) return;
            if (info.total === 0) { contenedor.innerHTML = ""; return; }
            contenedor.innerHTML = `
                <div style="display:flex;align-items:center;justify-content:space-between;gap:0.75rem;padding:0.6rem 0 0.2rem;flex-wrap:wrap;">
                    <span style="font-size:0.85rem;color:#9aa5b1;">${info.total} registro${info.total === 1 ? '' : 's'} · Página ${info.paginaActual} de ${info.totalPaginas}</span>
                    <div style="display:flex;gap:0.5rem;">
                        <button class="act-btn" ${info.paginaActual <= 1 ? 'disabled style="opacity:.4;cursor:not-allowed;"' : ''} onclick="cambiarPagina('${prefix}',-1,${renderFnNombre})" title="Anterior"><i class="fas fa-chevron-left"></i></button>
                        <button class="act-btn" ${info.paginaActual >= info.totalPaginas ? 'disabled style="opacity:.4;cursor:not-allowed;"' : ''} onclick="cambiarPagina('${prefix}',1,${renderFnNombre})" title="Siguiente"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>`;
        }

        let _regAdminData = [];

        async function cargarTablaRegistrosAdmin() {
            try {
                const res = await fetch("/api/asistencia");
                _regAdminData = await res.json();
                poblarFiltroGrados("reg-filtro-grado", _regAdminData);
                renderRegistrosAdminFiltrado();
            } catch(e) { console.error(e); }
        }

        // Llena un <select> de filtro con los grados que realmente existen
        // en los datos, sin duplicar opciones ni perder el grado seleccionado.
        function poblarFiltroGrados(selectId, data) {
            const select = document.getElementById(selectId);
            if (!select) return;
            const seleccionActual = select.value;
            const grados = [...new Set(data.map(a => a.grado).filter(Boolean))]
                .sort((a, b) => a.localeCompare(b, "es", { numeric: true }));
            select.innerHTML = `<option value="">Todos los grados</option>` +
                grados.map(g => `<option value="${g}">${g}</option>`).join("");
            if (grados.includes(seleccionActual)) select.value = seleccionActual;
        }

        // IDs de los campos de filtro de cada tabla, para poder limpiarlos de una sola vez
        const FILTRO_IDS = {
            reg:    { buscar: "reg-buscar",     grado: "reg-filtro-grado",     fecha: "reg-filtro-fecha" },
            repDoc: { buscar: "rep-doc-buscar", grado: "rep-doc-filtro-grado", fecha: "rep-doc-filtro-fecha" },
        };

        function limpiarFiltros(prefix, renderFn) {
            const ids = FILTRO_IDS[prefix];
            if (!ids) return;
            const buscar = document.getElementById(ids.buscar);
            const grado = document.getElementById(ids.grado);
            const fecha = document.getElementById(ids.fecha);
            if (buscar) buscar.value = "";
            if (grado) grado.value = "";
            if (fecha) fecha.value = "";
            resetPagina(prefix);
            renderFn();
        }

        function renderRegistrosAdminFiltrado() {
            const tbody = document.getElementById("reg-tbody-admin");
            if (!tbody) return;

            const busqueda = normalizarTexto(document.getElementById("reg-buscar")?.value);
            const grado = document.getElementById("reg-filtro-grado")?.value || "";
            const fecha = document.getElementById("reg-filtro-fecha")?.value || "";

            const dataFiltrada = ordenarDatos("reg", _regAdminData.filter(a => {
                const coincideBusqueda = !busqueda || normalizarTexto(a.nombre).includes(busqueda);
                const coincideGrado = !grado || a.grado === grado;
                const coincideFecha = !fecha || a.fecha === fecha;
                return coincideBusqueda && coincideGrado && coincideFecha;
            }));

            const info = paginarDatos("reg", dataFiltrada);
            renderControlesPaginacion("reg", info, "renderRegistrosAdminFiltrado");

            if (info.pageData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7"><div class="empty-st">No hay registros que coincidan con la búsqueda.</div></td></tr>`;
                return;
            }
            tbody.innerHTML = info.pageData.map(a => `
                <tr id="reg-row-${a.id}">
                    <td>${a.id}</td>
                    <td>${a.nombre}</td>
                    <td>${a.grado}</td>
                    <td>${a.fecha}</td>
                    <td>${a.hora}</td>
                    <td><span class="badge ${a.estado === 'Presente' ? 'presente' : a.estado === 'Retardo' ? 'retardo' : 'ausente'}">${a.estado}</span></td>
                    <td>
                        <div class="act-btns">
                            <button class="act-btn edit" onclick="editarRegistroAdmin(${a.id})" title="Editar"><i class="fas fa-pen"></i></button>
                            <button class="act-btn del"  onclick="eliminarRegistroAdmin(${a.id})" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        window.agregarRegistroAdmin = async function() {
            const err = document.getElementById("f-err");
            const select = document.getElementById("f-estudiante");
            const opt = select?.selectedOptions[0];
            const estudianteId = select?.value;
            const nombre = opt?.dataset.nombre;
            const grado = document.getElementById("f-grado")?.value;
            const estado = document.getElementById("f-estado")?.value;
            const fecha = document.getElementById("f-fecha")?.value;
            const hora = document.getElementById("f-hora")?.value;

            if (err) err.style.display = "none";

            if (!estudianteId || !nombre || !grado || !estado || !fecha || !hora) {
                if (err) {
                    err.textContent = "⚠ Selecciona un estudiante y completa todos los campos.";
                    err.style.display = "block";
                }
                return;
            }

            try {
                const res = await fetch("/api/asistencia", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ estudiante_id: estudianteId, nombre, grado, estado, fecha, hora })
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    const mensaje = errData.error
                        || errData.message
                        || (errData.errors ? Object.values(errData.errors).flat().join(" ") : null)
                        || "No se pudo guardar el registro.";
                    if (err) {
                        err.textContent = "⚠ " + mensaje;
                        err.style.display = "block";
                    }
                    return;
                }
                select.value = "";
                document.getElementById("f-grado").value = "";
                actualizarDashboardAdmin();
                mostrarToast("Registro guardado correctamente.");
            } catch(e) {
                console.error(e);
                if (err) {
                    err.textContent = "⚠ Error de conexión al guardar.";
                    err.style.display = "block";
                }
            }
        };

        window.editarRegistroAdmin = function(id) {
            const row = document.getElementById("reg-row-" + id);
            if (!row) { cargarTablaRegistrosAdmin(); return; }
            // Leer datos actuales directamente de las celdas del DOM
            const celdas = row.querySelectorAll("td");
            const nombre = celdas[1].textContent.trim();
            const grado  = celdas[2].textContent.trim();
            const fecha  = celdas[3].textContent.trim();
            const hora   = celdas[4].textContent.trim().slice(0,5);
            const estado = celdas[5].querySelector(".badge")?.textContent.trim() || "Presente";
            row.innerHTML =
                "<td>" + id + "</td>" +
                "<td><input type='text' class='pinp' id='re-nombre-" + id + "' value='" + nombre.replace(/'/g,"&#39;") + "' /></td>" +
                "<td><input type='text' class='pinp' id='re-grado-"  + id + "' value='" + grado  + "' /></td>" +
                "<td><input type='date' class='pinp' id='re-fecha-"  + id + "' value='" + fecha  + "' /></td>" +
                "<td><input type='time' class='pinp' id='re-hora-"   + id + "' value='" + hora   + "' /></td>" +
                "<td><select class='pinp'style='background:rgb(255, 255, 255, 0.05);' id='re-estado-" + id + "'>" +
                    "<option value='Presente'" + (estado==='Presente'?' selected':'') + ">Presente</option>" +
                    "<option value='Ausente'"  + (estado==='Ausente' ?' selected':'') + ">Ausente</option>"  +
                    "<option value='Retardo'"  + (estado==='Retardo' ?' selected':'') + ">Retardo</option>"  +
                "</select></td>" +
                "<td><div class='act-btns'>" +
                    "<button class='act-btn edit' onclick='guardarEdicionRegistroAdmin(" + id + ")' title='Guardar'><i class='fas fa-check'></i></button>" +
                    "<button class='act-btn del'  onclick='cargarTablaRegistrosAdmin()'             title='Cancelar'><i class='fas fa-times'></i></button>" +
                "</div></td>";
        };

        window.guardarEdicionRegistroAdmin = async function(id) {
            const nombre = document.getElementById("re-nombre-" + id)?.value.trim();
            const grado  = document.getElementById("re-grado-"  + id)?.value.trim();
            const estado = document.getElementById("re-estado-" + id)?.value;
            const fecha  = document.getElementById("re-fecha-"  + id)?.value;
            const hora   = document.getElementById("re-hora-"   + id)?.value;
            if (!nombre || !grado || !fecha || !hora) { mostrarToast("Completa todos los campos.", "error"); return; }
            try {
                const res = await fetch("/api/asistencia/" + id, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nombre, grado, estado, fecha, hora })
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    const mensaje = errData.message
                        || (errData.errors ? Object.values(errData.errors).flat().join(" ") : null)
                        || "No se pudo guardar el cambio.";
                    mostrarToast(mensaje, "error");
                    return;
                }
                cargarTablaRegistrosAdmin();
                actualizarDashboardAdmin();
                mostrarToast("Registro actualizado correctamente.");
            } catch(e) {
                mostrarToast("Error de conexión: " + e.message, "error");
            }
        };

        window.eliminarRegistroAdmin = async function(id) {
            if (!confirm("¿Eliminar este registro?")) return;
            try {
                const res = await fetch(`/api/asistencia/${id}`, {
                    method: "DELETE",
                    headers: {
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    mostrarToast(errData.message || "No se pudo eliminar el registro.", "error");
                    return;
                }
                cargarTablaRegistrosAdmin();
                actualizarDashboardAdmin();
                mostrarToast("Registro eliminado correctamente.");
            } catch(e) {
                mostrarToast("Error de conexión: " + e.message, "error");
            }
        };

        function cargarReportesAdmin() {
            const html = `
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-chart-bar" style="margin-right: 0.4rem; color: var(--blue-light);"></i>Todos los registros</h6></div>
                    <div class="p-card-bd" style="padding-top:0;padding-bottom:0.75rem;">
                        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:0.75rem;align-items:center;">
                            <input type="text" class="pinp" id="reg-buscar" placeholder="🔎 Buscar por nombre..." style="flex:1;min-width:220px;" oninput="resetPagina('reg');renderRegistrosAdminFiltrado()" />
                            <select class="pinp" id="reg-filtro-grado" style="min-width:180px;background:rgba(255,255,255,0.05)" onchange="resetPagina('reg');renderRegistrosAdminFiltrado()">
                                <option value="">Todos los grados</option>
                            </select>
                            <input type="date" class="pinp" id="reg-filtro-fecha" title="Filtrar por fecha" style="min-width:160px;background:rgba(255,255,255,0.05)" onchange="resetPagina('reg');renderRegistrosAdminFiltrado()" />
                            <button class="act-btn" title="Limpiar filtros" onclick="limpiarFiltros('reg', renderRegistrosAdminFiltrado)"><i class="fas fa-times"></i> Limpiar</button>
                             <!-- BOTÓN PDF AQUÍ -->
                    <button class="btn-pdf" onclick="exportarReporteAdminPDF()" style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: bold; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </button>
                    <!-- FIN BOTÓN -->
                        </div>
                    </div>
                    <div class="tbl-wrap"><table class="rtbl"><thead><tr>${thOrdenable('reg','id','ID','renderRegistrosAdminFiltrado')}${thOrdenable('reg','nombre','Estudiante','renderRegistrosAdminFiltrado')}${thOrdenable('reg','grado','Grado','renderRegistrosAdminFiltrado')}${thOrdenable('reg','fecha','Fecha','renderRegistrosAdminFiltrado')}${thOrdenable('reg','hora','Hora','renderRegistrosAdminFiltrado')}${thOrdenable('reg','estado','Estado','renderRegistrosAdminFiltrado')}<th>Acciones</th></tr></thead><tbody id="reg-tbody-admin"></tbody></table></div>
                    <div id="reg-paginacion" style="padding:0 1rem;"></div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;
            cargarTablaRegistrosAdmin();
        }

        // ======================================================
// EXPORTAR REPORTE EN PDF (Admin)
// ======================================================
// Descarga el PDF real, generado en el servidor con spatie/laravel-pdf,
// aplicando los mismos filtros que están activos en la tabla.
function exportarReporteAdminPDF() {
    const busqueda = document.getElementById('reg-buscar')?.value || '';
    const grado = document.getElementById('reg-filtro-grado')?.value || '';
    const fecha = document.getElementById('reg-filtro-fecha')?.value || '';

    if (_regAdminData.length === 0) {
        mostrarToast('No hay datos para exportar.', 'error');
        return;
    }

    const params = new URLSearchParams();
    if (busqueda) params.set('buscar', busqueda);
    if (grado) params.set('grado', grado);
    if (fecha) params.set('fecha', fecha);

    // Navegación normal (no fetch): el navegador maneja la descarga del
    // archivo directamente, enviando la sesión activa igual que cualquier
    // otra petición del panel.
    window.location.href = '/reporte/asistencia/pdf?' + params.toString();
}

        // ======================================================
        // 7. VISTA ADMIN: GRADOS (CRUD)
        // ======================================================
        function cargarGradosAdmin() {
            const html = `
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-plus-circle" style="margin-right: 0.4rem; color: var(--accent2);"></i>Nuevo Grado</h6></div>
                    <div class="p-card-bd">
                        <div class="form-grid">
                            <div><label class="flbl2">Nombre</label><input type="text" class="pinp" id="g-nombre" placeholder="Ej: 6°" maxlength="20" /></div>
                            <div><label class="flbl2">Descripción</label><input type="text" class="pinp" id="g-descripcion" placeholder="Opcional" maxlength="100" /></div>
                            <div style="display:flex;align-items:flex-end"><button class="btn-add" onclick="agregarGrado()"><i class="fas fa-plus"></i> Agregar</button></div>
                        </div>
                    </div>
                </div>
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-layer-group" style="margin-right: 0.4rem; color: var(--blue-light);"></i>Grados registrados</h6></div>
                    <div class="tbl-wrap"><table class="rtbl"><thead><tr><th>Nombre</th><th>Descripción</th><th>Nº estudiantes</th><th>Acciones</th></tr></thead><tbody id="grados-tbody"></tbody></table></div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;
            cargarGradosData();
        }

        let _gradosData = [];

        async function cargarGradosData() {
            try {
                const res = await fetch("/grados", { headers: { "Accept": "application/json" } });
                _gradosData = await res.json();
                renderGradosTabla();
            } catch(e) { console.error(e); mostrarToast("No se pudieron cargar los grados.", "error"); }
        }

        function renderGradosTabla() {
            const tbody = document.getElementById("grados-tbody");
            if (!tbody) return;
            if (_gradosData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4"><div class="empty-st">No hay grados registrados todavía.</div></td></tr>`;
                return;
            }
            const ordenados = [...(_gradosData)].sort((a, b) => (a.nombre || "").localeCompare(b.nombre || "", "es", { numeric: true }));
            tbody.innerHTML = ordenados.map(g => `
                <tr id="grado-row-${g.id}">
                    <td>${g.nombre ?? ""}</td>
                    <td>${g.descripcion ?? "—"}</td>
                    <td>${g.num_estudiantes ?? 0}</td>
                    <td>
                        <div class="act-btns">
                            <button class="act-btn edit" onclick="editarGrado(${g.id})" title="Editar"><i class="fas fa-pen"></i></button>
                            <button class="act-btn del"  onclick="eliminarGrado(${g.id})" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        window.agregarGrado = async function() {
            const nombre = document.getElementById("g-nombre")?.value.trim();
            const descripcion = document.getElementById("g-descripcion")?.value.trim();
            if (!nombre) { mostrarToast("El nombre del grado es obligatorio.", "error"); return; }
            try {
                const res = await fetch("/grados", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nombre, descripcion })
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    const mensaje = errData.message || (errData.errors ? Object.values(errData.errors).flat().join(" ") : "No se pudo crear el grado.");
                    mostrarToast(mensaje, "error");
                    return;
                }
                document.getElementById("g-nombre").value = "";
                document.getElementById("g-descripcion").value = "";
                cargarGradosData();
                mostrarToast("Grado agregado correctamente.");
            } catch(e) { mostrarToast("Error de conexión: " + e.message, "error"); }
        };

        window.editarGrado = function(id) {
            const row = document.getElementById("grado-row-" + id);
            if (!row) return;
            const g = _gradosData.find(x => x.id === id);
            if (!g) return;
            row.innerHTML =
                "<td><input type='text' class='pinp' id='ge-nombre-" + id + "' value='" + (g.nombre ?? "").replace(/'/g, "&#39;") + "' maxlength='20' /></td>" +
                "<td><input type='text' class='pinp' id='ge-descripcion-" + id + "' value='" + (g.descripcion ?? "").replace(/'/g, "&#39;") + "' maxlength='100' /></td>" +
                "<td>" + (g.num_estudiantes ?? 0) + "</td>" +
                "<td><div class='act-btns'>" +
                    "<button class='act-btn edit' onclick='guardarEdicionGrado(" + id + ")' title='Guardar'><i class='fas fa-check'></i></button>" +
                    "<button class='act-btn del'  onclick='renderGradosTabla()' title='Cancelar'><i class='fas fa-times'></i></button>" +
                "</div></td>";
        };

        window.guardarEdicionGrado = async function(id) {
            const nombre = document.getElementById("ge-nombre-" + id)?.value.trim();
            const descripcion = document.getElementById("ge-descripcion-" + id)?.value.trim();
            if (!nombre) { mostrarToast("El nombre del grado es obligatorio.", "error"); return; }
            try {
                const res = await fetch("/grados/" + id, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nombre, descripcion })
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    mostrarToast(errData.message || "No se pudo guardar el grado.", "error");
                    return;
                }
                await cargarGradosData();
                mostrarToast("Grado actualizado correctamente.");
            } catch(e) { mostrarToast("Error de conexión: " + e.message, "error"); }
        };

        window.eliminarGrado = async function(id) {
            if (!confirm("¿Eliminar este grado? Los horarios asociados también podrían quedar huérfanos.")) return;
            try {
                const res = await fetch("/grados/" + id, {
                    method: "DELETE",
                    headers: { "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    mostrarToast(errData.message || "No se pudo eliminar el grado.", "error");
                    return;
                }
                cargarGradosData();
                mostrarToast("Grado eliminado correctamente.");
            } catch(e) { mostrarToast("Error de conexión: " + e.message, "error"); }
        };

        // ======================================================
        // 8. VISTA ADMIN: GRUPOS (CRUD)
        //    Selección en cascada: primero Grado, después Número de
        //    grupo. El número NO tiene un tope fijo en el código —
        //    es un campo numérico abierto (para poder crear el grupo
        //    5, 10, o el que se necesite en el futuro sin tocar nada
        //    aquí), pero al elegir el grado se sugiere automáticamente
        //    el siguiente número disponible. El nombre final (ej:
        //    "6°1") lo arma el servidor solo, para que nadie lo escriba
        //    distinto.
        // ======================================================
        function cargarGruposAdmin() {
            const html = `
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-plus-circle" style="margin-right: 0.4rem; color: var(--accent2);"></i>Nuevo Grupo</h6></div>
                    <div class="p-card-bd">
                        <div class="form-grid">
                            <div><label class="flbl2">Grado</label>
                                <select class="pinp" id="gr-grado" style="background:rgba(255,255,255,0.05)" onchange="sugerirSiguienteNumero()">
                                    <option value="">Cargando grados…</option>
                                </select>
                            </div>
                            <div><label class="flbl2">Número de grupo</label>
                                <input type="number" class="pinp" id="gr-numero" min="1" step="1" placeholder="Selecciona un grado primero" oninput="actualizarPreviewGrupo()" />
                            </div>
                            <div><label class="flbl2">Vista previa</label><input type="text" class="pinp" id="gr-preview" readonly placeholder="Ej: 6°1" /></div>
                            <div style="display:flex;align-items:flex-end"><button class="btn-add" onclick="agregarGrupo()"><i class="fas fa-plus"></i> Agregar</button></div>
                        </div>
                        <div style="font-size:0.8rem;opacity:0.65;margin-top:0.35rem;">El número se sugiere solo (el siguiente disponible para ese grado), pero puedes escribir el que necesites.</div>
                    </div>
                </div>
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-users" style="margin-right: 0.4rem; color: var(--blue-light);"></i>Grupos registrados</h6></div>
                    <div class="tbl-wrap"><table class="rtbl"><thead><tr><th>Grado</th><th>Número</th><th>Nombre</th><th>Acciones</th></tr></thead><tbody id="grupos-tbody"></tbody></table></div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;
            cargarGruposData().then(() => cargarGradosParaSelectGrupo());
        }

        let _gruposData = [];
        let _gradosParaGrupo = [];

        async function cargarGradosParaSelectGrupo() {
            const select = document.getElementById("gr-grado");
            if (!select) return;
            try {
                const res = await fetch("/grados", { headers: { "Accept": "application/json" } });
                _gradosParaGrupo = await res.json();
                if (!_gradosParaGrupo.length) {
                    select.innerHTML = `<option value="">Primero crea un grado</option>`;
                    return;
                }
                const ordenados = [..._gradosParaGrupo].sort((a, b) => (a.nombre || "").localeCompare(b.nombre || "", "es", { numeric: true }));
                select.innerHTML = `<option value="">Selecciona un grado</option>` +
                    ordenados.map(g => `<option value="${g.id}">${g.nombre}</option>`).join("");
            } catch(e) {
                console.error(e);
                select.innerHTML = `<option value="">Error al cargar grados</option>`;
            }
        }

        // Al elegir un grado, sugiere el siguiente número libre (el mayor
        // que ya exista para ese grado, +1). Si el grado no tiene grupos
        // todavía, sugiere 1. Siempre se puede sobrescribir a mano.
        function sugerirSiguienteNumero() {
            const gradoId = Number(document.getElementById("gr-grado")?.value);
            const numeroInput = document.getElementById("gr-numero");
            if (!numeroInput) return;
            if (!gradoId) {
                numeroInput.placeholder = "Selecciona un grado primero";
                actualizarPreviewGrupo();
                return;
            }
            const numerosExistentes = _gruposData
                .filter(g => g.grado_id === gradoId)
                .map(g => g.numero);
            const siguiente = numerosExistentes.length ? Math.max(...numerosExistentes) + 1 : 1;
            numeroInput.value = siguiente;
            numeroInput.placeholder = "";
            actualizarPreviewGrupo();
        }

        function actualizarPreviewGrupo() {
            const gradoSelect = document.getElementById("gr-grado");
            const numero = document.getElementById("gr-numero")?.value;
            const preview = document.getElementById("gr-preview");
            if (!gradoSelect || !preview) return;
            const nombreGrado = gradoSelect.selectedOptions[0]?.textContent || "";
            preview.value = (gradoSelect.value && numero) ? (nombreGrado + numero) : "";
        }

        async function cargarGruposData() {
            try {
                const res = await fetch("/grupos", { headers: { "Accept": "application/json" } });
                _gruposData = await res.json();
                renderGruposTabla();
            } catch(e) { console.error(e); mostrarToast("No se pudieron cargar los grupos.", "error"); }
        }

        function renderGruposTabla() {
            const tbody = document.getElementById("grupos-tbody");
            if (!tbody) return;
            if (_gruposData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4"><div class="empty-st">No hay grupos registrados todavía.</div></td></tr>`;
                return;
            }
            tbody.innerHTML = _gruposData.map(g => `
                <tr id="grupo-row-${g.id}">
                    <td>${g.grado?.nombre ?? "—"}</td>
                    <td>${g.numero}</td>
                    <td>${g.nombre}</td>
                    <td>
                        <div class="act-btns">
                            <button class="act-btn edit" onclick="editarGrupo(${g.id})" title="Editar"><i class="fas fa-pen"></i></button>
                            <button class="act-btn del"  onclick="eliminarGrupo(${g.id})" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        window.agregarGrupo = async function() {
            const grado_id = document.getElementById("gr-grado")?.value;
            const numero = document.getElementById("gr-numero")?.value;
            if (!grado_id || !numero) { mostrarToast("Selecciona el grado y el número de grupo.", "error"); return; }
            try {
                const res = await fetch("/grupos", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ grado_id, numero })
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    const mensaje = errData.message || (errData.errors ? Object.values(errData.errors).flat().join(" ") : "No se pudo crear el grupo.");
                    mostrarToast(mensaje, "error");
                    return;
                }
                document.getElementById("gr-grado").value = "";
                document.getElementById("gr-numero").value = "";
                document.getElementById("gr-preview").value = "";
                cargarGruposData();
                mostrarToast("Grupo agregado correctamente.");
            } catch(e) { mostrarToast("Error de conexión: " + e.message, "error"); }
        };

        window.editarGrupo = function(id) {
            const row = document.getElementById("grupo-row-" + id);
            const g = _gruposData.find(x => x.id === id);
            if (!row || !g) return;

            const opcionesGrado = [..._gradosParaGrupo]
                .sort((a, b) => (a.nombre || "").localeCompare(b.nombre || "", "es", { numeric: true }))
                .map(gr => `<option value="${gr.id}" ${gr.id === g.grado_id ? "selected" : ""}>${gr.nombre}</option>`).join("");

            row.innerHTML =
                "<td><select class='pinp' id='ge-grado-" + id + "'>" + opcionesGrado + "</select></td>" +
                "<td><input type='number' class='pinp' id='ge-numero-" + id + "' min='1' step='1' value='" + g.numero + "' /></td>" +
                "<td>" + g.nombre + " <span style='opacity:.6;font-size:.75rem;'>(se recalcula al guardar)</span></td>" +
                "<td><div class='act-btns'>" +
                    "<button class='act-btn edit' onclick='guardarEdicionGrupo(" + id + ")' title='Guardar'><i class='fas fa-check'></i></button>" +
                    "<button class='act-btn del'  onclick='renderGruposTabla()' title='Cancelar'><i class='fas fa-times'></i></button>" +
                "</div></td>";
        };

        window.guardarEdicionGrupo = async function(id) {
            const grado_id = document.getElementById("ge-grado-" + id)?.value;
            const numero = document.getElementById("ge-numero-" + id)?.value;
            if (!grado_id || !numero) { mostrarToast("Selecciona el grado y el número de grupo.", "error"); return; }
            try {
                const res = await fetch("/grupos/" + id, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ grado_id, numero })
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    mostrarToast(errData.message || "No se pudo guardar el grupo.", "error");
                    return;
                }
                await cargarGruposData();
                mostrarToast("Grupo actualizado correctamente.");
            } catch(e) { mostrarToast("Error de conexión: " + e.message, "error"); }
        };

        window.eliminarGrupo = async function(id) {
            if (!confirm("¿Eliminar este grupo? Los estudiantes asignados a él podrían quedar huérfanos.")) return;
            try {
                const res = await fetch("/grupos/" + id, {
                    method: "DELETE",
                    headers: { "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    mostrarToast(errData.message || "No se pudo eliminar el grupo.", "error");
                    return;
                }
                cargarGruposData();
                mostrarToast("Grupo eliminado correctamente.");
            } catch(e) { mostrarToast("Error de conexión: " + e.message, "error"); }
        };

        // ======================================================
        // 9. VISTA ADMIN: HORARIOS (CRUD)
        // ======================================================
        function cargarHorariosAdmin() {
            const html = `
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-plus-circle" style="margin-right: 0.4rem; color: var(--accent2);"></i>Nuevo Horario</h6></div>
                    <div class="p-card-bd">
                        <div class="form-grid">
                            <div><label class="flbl2">Grado</label>
                                <select class="pinp" id="h-grado" style="background:rgba(255,255,255,0.05)">
                                    <option value="">Cargando grados…</option>
                                </select>
                            </div>
                            <div><label class="flbl2">Hora de entrada</label><input type="time" class="pinp" id="h-entrada" /></div>
                            <div><label class="flbl2">Hora límite (retardo)</label><input type="time" class="pinp" id="h-limite" /></div>
                            <div><label class="flbl2">Días</label><input type="text" class="pinp" id="h-dias" placeholder="Ej: Lunes a Viernes" maxlength="60" /></div>
                            <div style="display:flex;align-items:flex-end"><button class="btn-add" onclick="agregarHorario()"><i class="fas fa-plus"></i> Agregar</button></div>
                        </div>
                    </div>
                </div>
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-clock" style="margin-right: 0.4rem; color: var(--blue-light);"></i>Horarios registrados</h6></div>
                    <div class="tbl-wrap"><table class="rtbl"><thead><tr><th>Grado</th><th>Entrada</th><th>Hora límite</th><th>Días</th><th>Acciones</th></tr></thead><tbody id="horarios-tbody"></tbody></table></div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;
            cargarGradosParaSelectHorario();
            cargarHorariosData();
        }

        let _horariosData = [];

        async function cargarGradosParaSelectHorario() {
            const select = document.getElementById("h-grado");
            if (!select) return;
            try {
                const res = await fetch("/grados", { headers: { "Accept": "application/json" } });
                const grados = await res.json();
                if (!grados.length) {
                    select.innerHTML = `<option value="">Primero crea un grado</option>`;
                    return;
                }
                const ordenados = [...grados].sort((a, b) => (a.nombre || "").localeCompare(b.nombre || "", "es", { numeric: true }));
                select.innerHTML = `<option value="">Selecciona un grado</option>` +
                    ordenados.map(g => `<option value="${g.id}">${g.nombre}</option>`).join("");
            } catch(e) {
                console.error(e);
                select.innerHTML = `<option value="">Error al cargar grados</option>`;
            }
        }

        async function cargarHorariosData() {
            try {
                const res = await fetch("/horarios", { headers: { "Accept": "application/json" } });
                _horariosData = await res.json();
                renderHorariosTabla();
            } catch(e) { console.error(e); mostrarToast("No se pudieron cargar los horarios.", "error"); }
        }

        function renderHorariosTabla() {
            const tbody = document.getElementById("horarios-tbody");
            if (!tbody) return;
            if (_horariosData.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5"><div class="empty-st">No hay horarios registrados todavía.</div></td></tr>`;
                return;
            }
            tbody.innerHTML = _horariosData.map(h => `
                <tr id="horario-row-${h.id}">
                    <td>${h.grado?.nombre ?? "—"}</td>
                    <td>${(h.hora_entrada || "").slice(0,5)}</td>
                    <td>${(h.hora_limite || "").slice(0,5)}</td>
                    <td>${h.dias ?? "—"}</td>
                    <td>
                        <div class="act-btns">
                            <button class="act-btn edit" onclick="editarHorario(${h.id})" title="Editar"><i class="fas fa-pen"></i></button>
                            <button class="act-btn del"  onclick="eliminarHorario(${h.id})" title="Eliminar"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        window.agregarHorario = async function() {
            const grado_id = document.getElementById("h-grado")?.value;
            const hora_entrada = document.getElementById("h-entrada")?.value;
            const hora_limite = document.getElementById("h-limite")?.value;
            const dias = document.getElementById("h-dias")?.value.trim();
            if (!grado_id || !hora_entrada || !hora_limite) { mostrarToast("Selecciona el grado y completa las horas.", "error"); return; }
            try {
                const res = await fetch("/horarios", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ grado_id, hora_entrada, hora_limite, dias })
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    const mensaje = errData.message || (errData.errors ? Object.values(errData.errors).flat().join(" ") : "No se pudo crear el horario.");
                    mostrarToast(mensaje, "error");
                    return;
                }
                document.getElementById("h-grado").value = "";
                document.getElementById("h-entrada").value = "";
                document.getElementById("h-limite").value = "";
                document.getElementById("h-dias").value = "";
                cargarHorariosData();
                mostrarToast("Horario agregado correctamente.");
            } catch(e) { mostrarToast("Error de conexión: " + e.message, "error"); }
        };

        window.editarHorario = function(id) {
            const row = document.getElementById("horario-row-" + id);
            const h = _horariosData.find(x => x.id === id);
            if (!row || !h) return;
            row.innerHTML =
                "<td>" + (h.grado?.nombre ?? "—") + "</td>" +
                "<td><input type='time' class='pinp' id='he-entrada-" + id + "' value='" + (h.hora_entrada || "").slice(0,5) + "' /></td>" +
                "<td><input type='time' class='pinp' id='he-limite-"  + id + "' value='" + (h.hora_limite  || "").slice(0,5) + "' /></td>" +
                "<td><input type='text' class='pinp' id='he-dias-"    + id + "' value='" + (h.dias ?? "").replace(/'/g, "&#39;") + "' /></td>" +
                "<td><div class='act-btns'>" +
                    "<button class='act-btn edit' onclick='guardarEdicionHorario(" + id + ")' title='Guardar'><i class='fas fa-check'></i></button>" +
                    "<button class='act-btn del'  onclick='renderHorariosTabla()' title='Cancelar'><i class='fas fa-times'></i></button>" +
                "</div></td>";
        };

        window.guardarEdicionHorario = async function(id) {
            const hora_entrada = document.getElementById("he-entrada-" + id)?.value;
            const hora_limite = document.getElementById("he-limite-" + id)?.value;
            const dias = document.getElementById("he-dias-" + id)?.value.trim();
            if (!hora_entrada || !hora_limite) { mostrarToast("Completa ambas horas.", "error"); return; }
            try {
                const res = await fetch("/horarios/" + id, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ hora_entrada, hora_limite, dias })
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    mostrarToast(errData.message || "No se pudo guardar el horario.", "error");
                    return;
                }
                await cargarHorariosData();
                mostrarToast("Horario actualizado correctamente.");
            } catch(e) { mostrarToast("Error de conexión: " + e.message, "error"); }
        };

        window.eliminarHorario = async function(id) {
            if (!confirm("¿Eliminar este horario?")) return;
            try {
                const res = await fetch("/horarios/" + id, {
                    method: "DELETE",
                    headers: { "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    mostrarToast(errData.message || "No se pudo eliminar el horario.", "error");
                    return;
                }
                cargarHorariosData();
                mostrarToast("Horario eliminado correctamente.");
            } catch(e) { mostrarToast("Error de conexión: " + e.message, "error"); }
        };

        // ======================================================
        // 10. LOGOUT Y INICIALIZACIÓN
        // ======================================================
        async function doLogout() {
            try {
                await fetch("/api/logout", {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                });
            } catch (e) {
                console.error(e);
            }
            sessionStorage.removeItem("auta_user");
            sessionStorage.removeItem("auta_role");
            window.location.href = "/login";
        }

        // Inicializar
        cargarMenu();
        
        if (_roleKey === "estudiante") {
            mostrarVista("miAsistencia");
        } else if (_roleKey === "docente") {
            // Docente: muestra directamente los reportes en el panel
            mostrarVista("reportesDocente");
        } else {
            mostrarVista("dashboard");
        }
    </script>
</body>
</html>