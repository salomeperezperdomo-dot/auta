      // ═══════════════════════════════════════════
      //  CONEXIÓN ENTRE LAS 3 VISTAS (Inicio, Login, Panel)
      //  showPage() es la función central de navegación.
      //  Oculta todas las "páginas" y muestra solo la que se pide.
      //  La uso para: Inicio→Login, Login→Panel, Panel→Inicio y Logout→Login
      // ═══════════════════════════════════════════
      function showPage(pageId) {
        document
          .querySelectorAll(".app-page")
          .forEach((p) => p.classList.remove("active"));
        document.getElementById(pageId).classList.add("active");
        window.scrollTo(0, 0);
      }

      // ═══════════════════════════════════════════
      //  LÓGICA DEL SIDEBAR HAMBURGUESA (Plantilla Sidebars de Bootstrap)
      //  toggleSidebar() abre y cierra el menú lateral.
      //  closeSidebar() lo cierra al hacer clic en el overlay oscuro.
      //  goSection() cambia la sección visible del sitio sin recargar.
      // ═══════════════════════════════════════════
      function toggleSidebar() {
        document.getElementById("siteSidebar").classList.toggle("open");
        document.getElementById("siteOverlay").classList.toggle("show");
      }
      function closeSidebar() {
        document.getElementById("siteSidebar").classList.remove("open");
        document.getElementById("siteOverlay").classList.remove("show");
      }
      function goSection(id, btn) {
        document
          .querySelectorAll(".site-section")
          .forEach((s) => s.classList.remove("active"));
        document.getElementById("sec-" + id).classList.add("active");
        document
          .querySelectorAll(".sb-btn")
          .forEach((b) => b.classList.remove("active"));
        const match = document.querySelector(`.sb-btn[data-sec="${id}"]`);
        if (match) match.classList.add("active");
        closeSidebar();
        window.scrollTo({ top: 0, behavior: "smooth" });
      }

      // ═══════════════════════════════════════════
      //  LÓGICA DEL LOGIN (Plantilla Sign-in de Bootstrap adaptada)
      //  Acá defino los 3 usuarios del sistema con sus contraseñas y roles.
      //  doLogin() verifica que usuario + contraseña + rol coincidan.
      //  Si es correcto conecta con el panel usando showPage("page-panel").
      //  doLogout() limpia el formulario y vuelve al login.
      // ═══════════════════════════════════════════
      const USERS = {
        admin: { pass: "admin123", role: "admin", label: "Administrador" },
        docente: { pass: "doc123", role: "docente", label: "Docente" },
        estudiante: { pass: "est123", role: "estudiante", label: "Estudiante" },
      };
      let currentRole = "admin";

      function selectRole(r) {
        currentRole = r;
        document
          .querySelectorAll(".role-btn")
          .forEach((b) => b.classList.remove("sel"));
        document.getElementById("role-" + r).classList.add("sel");
      }
      function doLogin() {
        const user = document.getElementById("lUser").value.trim();
        const pass = document.getElementById("lPass").value;
        const err = document.getElementById("loginError");
        const msg = document.getElementById("loginMsg");
        const found = USERS[user];
        if (!found || found.pass !== pass || found.role !== currentRole) {
          msg.textContent = "Usuario, contraseña o rol incorrecto.";
          err.classList.add("show");
          return;
        }
        err.classList.remove("show");
        document.getElementById("sbUser").textContent = user;
        document.getElementById("sbRole").textContent = found.label;
        showPage("page-panel");
        showView("dashboard", null);
        updateAll();
      }
      function doLogout() {
        document.getElementById("lUser").value = "";
        document.getElementById("lPass").value = "";
        showPage("page-login");
      }

      // ═══════════════════════════════════════════
      //  PANEL – vistas
      // ═══════════════════════════════════════════
      const viewTitles = {
        dashboard: "Dashboard",
        registro: "Registrar Asistencia",
        reportes: "Reportes",
      };
      function showView(v, btn) {
        document
          .querySelectorAll(".p-view")
          .forEach((x) => x.classList.remove("active"));
        document.getElementById("v-" + v).classList.add("active");
        document
          .querySelectorAll(".psb-btn")
          .forEach((b) => b.classList.remove("active"));
        if (btn) btn.classList.add("active");
        else {
          const btns = document.querySelectorAll(".psb-btn");
          btns.forEach((b) => {
            if (
              b.textContent
                .trim()
                .toLowerCase()
                .includes(v.slice(0, 4).toLowerCase())
            )
              b.classList.add("active");
          });
        }
        document.getElementById("pTopTitle").textContent = viewTitles[v] || v;
        updateAll();
      }

      // ═══════════════════════════════════════════
      //  OPERACIONES CRUD DEL SISTEMA
      //  Implementé las 4 operaciones básicas de gestión de datos:
      //  CREATE → addRec()   agrega un nuevo registro de asistencia
      //  READ   → renderDash/Reg/Rep()   muestra los registros en pantalla
      //  UPDATE → openEdit() + saveEdit()   edita un registro existente
      //  DELETE → delRec()   elimina un registro con confirmación
      //  Todos los datos viven en el arreglo "records" en memoria
      // ═══════════════════════════════════════════
      let records = [],
        nextId = 1;
      let filtState = { grado: "Todos", estado: "Todos" };
      let repState = {
        grado: "Todos",
        estado: "Todos",
        periodo: "todos",
        fechaExacta: "",
      };
      let editingId = null,
        editTmp = {};
      const editModal = new bootstrap.Modal(
        document.getElementById("editModal"),
      );

      // — Helpers para los dropdowns del formulario: guardan el valor y actualizan el label —
      function setF(field, val, lblId) {
        document.getElementById("f-" + field).value = val;
        document.getElementById(lblId).textContent = val;
      }
      function setFilt(field, val, lblId) {
        filtState[field] = val;
        document.getElementById(lblId).textContent = val;
        renderReg();
      }

      // — CREATE: agrego un nuevo registro al arreglo records —
      function addRec() {
        const nombre = document.getElementById("f-nombre").value.trim();
        const grado = document.getElementById("f-grado").value;
        const estado = document.getElementById("f-estado").value;
        const fecha = document.getElementById("f-fecha").value;
        const hora = document.getElementById("f-hora").value;
        const errEl = document.getElementById("f-err");
        if (!nombre || !grado || !estado || !fecha || !hora) {
          errEl.textContent = "⚠ Completa todos los campos.";
          errEl.classList.add("show");
          return;
        }
        errEl.classList.remove("show");
        records.unshift({ id: nextId++, nombre, grado, estado, fecha, hora });
        document.getElementById("f-nombre").value = "";
        document.getElementById("f-grado").value = "";
        document.getElementById("f-estado").value = "";
        document.getElementById("f-grado-lbl").textContent =
          "Seleccionar grado";
        document.getElementById("f-estado-lbl").textContent =
          "Seleccionar estado";
        updateAll();
      }
      // — DELETE: elimino el registro con ese id del arreglo —
      function delRec(id) {
        if (!confirm("¿Eliminar este registro?")) return;
        records = records.filter((r) => r.id !== id);
        updateAll();
      }
      // — UPDATE paso 1: cargo los datos del registro en el Modal de Bootstrap —
      function openEdit(id) {
        const r = records.find((r) => r.id === id);
        if (!r) return;
        editingId = id;
        editTmp = { grado: r.grado, estado: r.estado };
        document.getElementById("e-id").value = id;
        document.getElementById("e-nombre").value = r.nombre;
        document.getElementById("e-grado").value = r.grado;
        document.getElementById("e-estado").value = r.estado;
        document.getElementById("e-fecha").value = r.fecha;
        document.getElementById("e-hora").value = r.hora;
        document.getElementById("e-grado-lbl").textContent = r.grado;
        document.getElementById("e-estado-lbl").textContent = r.estado;
        editModal.show();
      }
      function setE(field, val, lblId) {
        editTmp[field] = val;
        document.getElementById("e-" + field).value = val;
        document.getElementById(lblId).textContent = val;
      }
      // — UPDATE paso 2: guardo los cambios del modal en el arreglo —
      function saveEdit() {
        const r = records.find((r) => r.id === editingId);
        if (!r) return;
        r.nombre = document.getElementById("e-nombre").value.trim() || r.nombre;
        r.grado = editTmp.grado || r.grado;
        r.estado = editTmp.estado || r.estado;
        r.fecha = document.getElementById("e-fecha").value || r.fecha;
        r.hora = document.getElementById("e-hora").value || r.hora;
        editModal.hide();
        updateAll();
      }

      // — Badge —
      function badge(estado) {
        const map = {
          Presente: ["presente", "fa-check-circle"],
          Ausente: ["ausente", "fa-times-circle"],
          Retardo: ["retardo", "fa-clock"],
        };
        const [c, i] = map[estado] || ["", ""];
        return `<span class="badge ${c}"><i class="fas ${i}"></i>${estado}</span>`;
      }

      // — Render tables —
      // — READ: muestro los últimos 8 registros en el dashboard —
      function renderDash() {
        const tbody = document.getElementById("dash-tbody");
        const rows = records.slice(0, 8);
        tbody.innerHTML = rows.length
          ? rows
              .map(
                (r) =>
                  `<tr><td>${r.nombre}</td><td>${r.grado}</td><td>${r.fecha}</td><td>${r.hora}</td><td>${badge(r.estado)}</td></tr>`,
              )
              .join("")
          : `<tr><td colspan="5"><div class="empty-st"><i class="fas fa-inbox"></i>Sin registros. <a onclick="showView('registro',null)">Crea el primero</a></div></td></tr>`;
      }
      // — READ con filtros: muestro los registros según grado y estado seleccionados —
      function renderReg() {
        const filtered = records.filter(
          (r) =>
            (filtState.grado === "Todos" || r.grado === filtState.grado) &&
            (filtState.estado === "Todos" || r.estado === filtState.estado),
        );
        const tbody = document.getElementById("reg-tbody");
        tbody.innerHTML = filtered.length
          ? filtered
              .map(
                (r) =>
                  `<tr><td style="color:var(--muted);font-size:.76rem;">#${r.id}</td><td>${r.nombre}</td><td>${r.grado}</td><td>${r.fecha}</td><td>${r.hora}</td><td>${badge(r.estado)}</td><td><div class="act-btns"><button class="act-btn edit" onclick="openEdit(${r.id})"><i class="fas fa-pen"></i></button><button class="act-btn del" onclick="delRec(${r.id})"><i class="fas fa-trash"></i></button></div></td></tr>`,
              )
              .join("")
          : `<tr><td colspan="7"><div class="empty-st"><i class="fas fa-inbox"></i>Sin registros con esos filtros.</div></td></tr>`;
      }

      // — Reporte —
      function setRep(field, val, lblId) {
        repState[field] = val;
        repState.fechaExacta = "";
        document.getElementById("rFecha").value = "";
        document.getElementById(lblId).textContent = val;
        renderRep();
      }
      function setPeriodo(p, display) {
        repState.periodo = p;
        repState.fechaExacta = "";
        document.getElementById("rFecha").value = "";
        document.getElementById("rPeriodo").textContent = display;
        renderRep();
      }
      function setFechaExacta(val) {
        repState.fechaExacta = val;
        repState.periodo = "todos";
        document.getElementById("rPeriodo").textContent = val
          ? "📅 " + val
          : "Todo el tiempo";
        renderRep();
      }
      function resetRep() {
        repState = {
          grado: "Todos",
          estado: "Todos",
          periodo: "todos",
          fechaExacta: "",
        };
        ["rGrado", "rEstado"].forEach(
          (id) => (document.getElementById(id).textContent = "Todos"),
        );
        document.getElementById("rPeriodo").textContent = "Todo el tiempo";
        document.getElementById("rFecha").value = "";
        renderRep();
      }
      function inPeriod(f) {
        if (repState.fechaExacta) return f === repState.fechaExacta;
        if (repState.periodo === "todos") return true;
        const d = new Date(f + "T00:00:00"),
          now = new Date();
        if (repState.periodo === "hoy")
          return f === now.toISOString().slice(0, 10);
        if (repState.periodo === "semana") {
          const w = new Date(now);
          w.setDate(now.getDate() - 7);
          return d >= w;
        }
        if (repState.periodo === "mes")
          return (
            d.getMonth() === now.getMonth() &&
            d.getFullYear() === now.getFullYear()
          );
        return true;
      }
      // — READ de Reportes: aplico los 4 filtros (grado, estado, período, fecha)
      //   y actualizo los contadores KPI y la tabla de resultados —
      function renderRep() {
        const filtered = records.filter(
          (r) =>
            (repState.grado === "Todos" || r.grado === repState.grado) &&
            (repState.estado === "Todos" || r.estado === repState.estado) &&
            inPeriod(r.fecha),
        );
        document.getElementById("r-total").textContent = filtered.length;
        document.getElementById("r-pres").textContent = filtered.filter(
          (r) => r.estado === "Presente",
        ).length;
        document.getElementById("r-aus").textContent = filtered.filter(
          (r) => r.estado === "Ausente",
        ).length;
        document.getElementById("r-ret").textContent = filtered.filter(
          (r) => r.estado === "Retardo",
        ).length;
        document.getElementById("r-count").textContent = filtered.length
          ? `· ${filtered.length} resultado${filtered.length !== 1 ? "s" : ""}`
          : "";

        // Chips
        const chips = [];
        if (repState.grado !== "Todos")
          chips.push(
            `<span class="chip">Grado: ${repState.grado} <span class="chip-x" onclick="setRep('grado','Todos','rGrado')">✕</span></span>`,
          );
        if (repState.estado !== "Todos")
          chips.push(
            `<span class="chip">Estado: ${repState.estado} <span class="chip-x" onclick="setRep('estado','Todos','rEstado')">✕</span></span>`,
          );
        if (repState.fechaExacta)
          chips.push(
            `<span class="chip">Fecha: ${repState.fechaExacta} <span class="chip-x" onclick="setFechaExacta('')">✕</span></span>`,
          );
        else if (repState.periodo !== "todos")
          chips.push(
            `<span class="chip">Período: ${repState.periodo} <span class="chip-x" onclick="setPeriodo('todos','Todo el tiempo')">✕</span></span>`,
          );
        document.getElementById("chip-wrap").innerHTML = chips.join("");

        const tbody = document.getElementById("rep-tbody");
        tbody.innerHTML = filtered.length
          ? filtered
              .map(
                (r) =>
                  `<tr><td>${r.nombre}</td><td>${r.grado}</td><td>${r.fecha}</td><td>${r.hora}</td><td>${badge(r.estado)}</td></tr>`,
              )
              .join("")
          : `<tr><td colspan="5"><div class="empty-st"><i class="fas fa-search"></i>Sin resultados para estos filtros.</div></td></tr>`;
      }

      // — KPIs —
      function updateKPIs() {
        document.getElementById("k-total").textContent = records.length;
        document.getElementById("k-pres").textContent = records.filter(
          (r) => r.estado === "Presente",
        ).length;
        document.getElementById("k-aus").textContent = records.filter(
          (r) => r.estado === "Ausente",
        ).length;
        document.getElementById("k-ret").textContent = records.filter(
          (r) => r.estado === "Retardo",
        ).length;
      }
      function updateAll() {
        updateKPIs();
        renderDash();
        renderReg();
        renderRep();
      }

      // — Fecha/hora por defecto —
      window.addEventListener("DOMContentLoaded", () => {
        const now = new Date();
        document.getElementById("f-fecha").value = now
          .toISOString()
          .slice(0, 10);
        document.getElementById("f-hora").value = now
          .toTimeString()
          .slice(0, 5);
      });
