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
                    <small>I.E. San José</small>
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
        // 1. RECUPERAR SESIÓN
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
                const res = await fetch("/api/asistencia");
                const data = await res.json();
                const misAsistencias = data.filter(a => a.nombre === _user || a.nombre.toLowerCase() === _user.toLowerCase());
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
                    <div class="tbl-wrap">
                        <table class="rtbl">
                            <thead>
                                <tr><th>Estudiante</th><th>Grado</th><th>Fecha</th><th>Hora</th><th>Estado</th></tr>
                            </thead>
                            <tbody id="reportes-tbody"></tbody>
                        </table>
                    </div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;

            try {
                const res = await fetch("/api/asistencia");
                const data = await res.json();
                const tbody = document.getElementById("reportes-tbody");
                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5"><div class="empty-st">No hay registros de asistencia.</div></td></tr>`;
                    return;
                }
                tbody.innerHTML = data.map(a => `
                    <tr>
                        <td>${a.nombre}</td>
                        <td>${a.grado}</td>
                        <td>${a.fecha}</td>
                        <td>${a.hora}</td>
                        <td><span class="badge ${a.estado === 'Presente' ? 'presente' : a.estado === 'Retardo' ? 'retardo' : 'ausente'}">${a.estado}</span></td>
                    </tr>
                `).join('');
            } catch(e) {
                console.error(e);
            }
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
                            <div><label class="flbl2">Estudiante</label><input type="text" class="pinp" id="f-nombre" placeholder="Nombre completo" /></div>
                            <div><label class="flbl2">Grado</label><input type="text" class="pinp" id="f-grado" placeholder="Ej: 10°" /></div>
                            <div><label class="flbl2">Estado</label>
                                <select class="pinp" id="edit-estado" style="background:rgba(255, 255, 255, 0.05)">
                                    <option value="Presente">Presente</option>
                                    <option value="Ausente">Ausente</option>
                                    <option value="Retardo">Retardo</option>
                                </select>
                            </div>
                            <div><label class="flbl2">Fecha</label><input type="date" class="pinp" id="f-fecha" /></div>
                            <div><label class="flbl2">Hora</label><input type="time" class="pinp" id="f-hora" /></div>
                            <div style="display: flex; align-items: flex-end"><button class="btn-add" onclick="agregarRegistroAdmin()"><i class="fas fa-plus"></i> Registrar</button></div>
                        </div>
                    </div>
                </div>
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-list" style="margin-right: 0.4rem; color: var(--blue-light);"></i>Todos los registros</h6></div>
                    <div class="tbl-wrap"><table class="rtbl"><thead><tr><th>ID</th><th>Estudiante</th><th>Grado</th><th>Fecha</th><th>Hora</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="reg-tbody-admin"></tbody></table></div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;
            const hoy = new Date();
            document.getElementById("f-fecha").value = hoy.toISOString().split("T")[0];
            document.getElementById("f-hora").value = hoy.toTimeString().slice(0, 5);
            cargarTablaRegistrosAdmin();
        }

        async function cargarTablaRegistrosAdmin() {
            try {
                const res = await fetch("/api/asistencia");
                const data = await res.json();
                const tbody = document.getElementById("reg-tbody-admin");
                if (!tbody) return;
                if (data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="7"><div class="empty-st">Sin registros</div></tr></tr>`;
                    return;
                }
                tbody.innerHTML = data.map(a => `
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
            } catch(e) { console.error(e); }
        }

        window.agregarRegistroAdmin = async function() {
            const nombre = document.getElementById("f-nombre")?.value.trim();
            const grado = document.getElementById("f-grado")?.value;
            const estado = document.getElementById("f-estado")?.value;
            const fecha = document.getElementById("f-fecha")?.value;
            const hora = document.getElementById("f-hora")?.value;
            if (!nombre || !grado || !fecha || !hora) {
                alert("Completa todos los campos");
                return;
            }
            try {
                await fetch("/api/asistencia", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ nombre, grado, estado, fecha, hora })
                });
                document.getElementById("f-nombre").value = "";
                document.getElementById("f-grado").value = "";
                cargarTablaRegistrosAdmin();
                actualizarDashboardAdmin();
            } catch(e) { console.error(e); }
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
            if (!nombre || !grado || !fecha || !hora) { alert("Completa todos los campos."); return; }
            try {
                const res = await fetch("/api/asistencia/" + id, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ nombre, grado, estado, fecha, hora })
                });
                if (!res.ok) {
                    const err = await res.json().catch(() => ({}));
                    alert("Error al guardar: " + (err.message || res.status));
                    return;
                }
                cargarTablaRegistrosAdmin();
                actualizarDashboardAdmin();
            } catch(e) { alert("Error de conexión: " + e.message); }
        };

        window.eliminarRegistroAdmin = async function(id) {
            if (!confirm("¿Eliminar este registro?")) return;
            try {
                await fetch(`/api/asistencia/${id}`, { method: "DELETE", headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content } });
                cargarTablaRegistrosAdmin();
                actualizarDashboardAdmin();
            } catch(e) { console.error(e); }
        };

        function cargarReportesAdmin() {
            const html = `
                <div class="p-card">
                    <div class="p-card-hd"><h6><i class="fas fa-chart-bar" style="margin-right: 0.4rem; color: var(--blue-light);"></i>Todos los registros</h6></div>
                    <div class="tbl-wrap"><table class="rtbl"><thead><tr><th>Estudiante</th><th>Grado</th><th>Fecha</th><th>Hora</th><th>Estado</th></tr></thead><tbody id="reportes-admin-tbody"></tbody></table></div>
                </div>
            `;
            document.getElementById("contenido-dinamico").innerHTML = html;
            cargarReportesAdminData();
        }

        async function cargarReportesAdminData() {
            try {
                const res = await fetch("/api/asistencia");
                const data = await res.json();
                const tbody = document.getElementById("reportes-admin-tbody");
                if (!tbody) return;
                tbody.innerHTML = data.map(a => `
                    <tr><td>${a.nombre}</td><td>${a.grado}</td><td>${a.fecha}</td><td>${a.hora}</td><td><span class="badge ${a.estado === 'Presente' ? 'presente' : a.estado === 'Retardo' ? 'retardo' : 'ausente'}">${a.estado}</span></td></tr>
                `).join('');
            } catch(e) { console.error(e); }
        }

        // ======================================================
        // 7. LOGOUT Y INICIALIZACIÓN
        // ======================================================
        function doLogout() {
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