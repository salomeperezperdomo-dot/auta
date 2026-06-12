<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login · AUTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}" />

    <style>
      .emoji-face {
        font-size: 72px;
        text-align: center;
        margin: 0 auto 0.25rem;
        cursor: pointer;
        user-select: none;
        line-height: 1;
        display: block;
        -webkit-tap-highlight-color: transparent;
      }
      @keyframes emojiBounce {
        0%   { transform: scale(1); }
        30%  { transform: scale(1.3) rotate(-8deg); }
        60%  { transform: scale(0.95) rotate(4deg); }
        100% { transform: scale(1) rotate(0deg); }
      }
      @keyframes emojiShake {
        0%,100% { transform: translateX(0); }
        20%     { transform: translateX(-8px); }
        40%     { transform: translateX(8px); }
        60%     { transform: translateX(-6px); }
        80%     { transform: translateX(6px); }
      }
      .emojiBounce { animation: emojiBounce 0.4s ease; }
      .emojiShake  { animation: emojiShake 0.5s ease; }
    </style>
  </head>
  <body>
    <div class="app-page active" id="page-login">
      <div
        style="
          position: relative;
          z-index: 1;
          display: flex;
          align-items: center;
          justify-content: center;
          min-height: 100vh;
          padding: 1.5rem;
        "
      >
        <div class="login-box">
          <div class="back-lnk" onclick="window.location.href='/'">
            <i class="fas fa-arrow-left"></i> Volver al inicio
          </div>
          <div class="login-card">
            <div class="emoji-face" id="emojiFace" title="¡Tócame!">👀</div>
            <h2>Bienvenido</h2>
            <p class="sub">Selecciona tu rol e inicia sesión</p>

            <!-- Selector de roles: adapté el Sign-in de Bootstrap añadiendo
             este bloque. El usuario elige su rol antes de ingresar y
             doLogin() verifica que coincida con las credenciales -->
            <div class="role-grid">
              <button
                class="role-btn sel"
                id="role-admin"
                onclick="selectRole('admin')"
              >
                <i class="fas fa-user-shield"></i>Administrador
              </button>
              <button
                class="role-btn"
                id="role-docente"
                onclick="selectRole('docente')"
              >
                <i class="fas fa-chalkboard-teacher"></i>Docente
              </button>
              <button
                class="role-btn"
                id="role-estudiante"
                onclick="selectRole('estudiante')"
              >
                <i class="fas fa-user-graduate"></i>Estudiante
              </button>
            </div>

            <div class="alert-err" id="loginError">
              <i class="fas fa-exclamation-circle"></i
              ><span id="loginMsg">Credenciales incorrectas.</span>
            </div>

            <div class="inp-wrap">
              <i class="fas fa-user"></i>
              <input
                type="text"
                class="linp"
                id="lUser"
                placeholder="Usuario"
              />
            </div>
            <div class="inp-wrap">
              <i class="fas fa-lock"></i>
              <input
                type="password"
                class="linp"
                id="lPass"
                placeholder="••••••••"
              />
            </div>
            <div class="rem-row">
              <label class="chk-lbl"
                ><input type="checkbox" /> Recordarme</label
              >
              <a href="#" class="fgt-lnk">¿Olvidaste tu contraseña?</a>
            </div>
            <!-- Conexión Login → Panel: doLogin() valida usuario + contraseña + rol
             y si todo coincide llama a showPage("page-panel") para ir al panel -->
            <button class="btn-doLogin" onclick="doLogin()">
              <i class="fas fa-sign-in-alt"></i> Iniciar sesión
            </button>

            <div class="cred-hint">
              <strong
                ><i class="fas fa-info-circle"></i> Credenciales de
                demostración</strong
              >
              Admin: <code>admin</code> / <code>admin123</code><br />
              Docente: <code>docente</code> / <code>doc123</code><br />
              Estudiante: <code>estudiante</code> / <code>est123</code>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════
     ③ PANEL DE ADMINISTRACIÓN
     Solo se accede aquí después de hacer login correctamente.
     Apliqué dos plantillas de Bootstrap:
       - Sidebars: para el menú lateral fijo de navegación
       - Dropdowns: para los filtros de grado, estado, período y fecha
     También implementé el CRUD completo:
       CREATE → addRec()     | READ  → renderDash/Reg/Rep()
       UPDATE → saveEdit()   | DELETE → delRec()
══════════════════════════════════════════════════════ -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
          if (window.yetiReact) yetiReact(false);
          return;
        }
        err.classList.remove("show");
        if (window.yetiReact) yetiReact(true);
        sessionStorage.setItem("auta_user", user);
        sessionStorage.setItem("auta_role", found.label);
        setTimeout(function () { window.location.href = "/panel"; }, 900);
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
      const editModalEl = document.getElementById("editModal");
      const editModal = editModalEl ? new bootstrap.Modal(editModalEl) : null;

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

      // — Fecha/hora por defecto + Emoji reactivo AUTA —
      window.addEventListener("DOMContentLoaded", function () {
        const now = new Date();
        const fFecha = document.getElementById("f-fecha");
        const fHora  = document.getElementById("f-hora");
        if (fFecha) fFecha.value = now.toISOString().slice(0, 10);
        if (fHora)  fHora.value  = now.toTimeString().slice(0, 5);

        // ═══════════════════════════════════════════
        //  EMOJI REACTIVO AUTA
        //  👀 neutro  · 🧐 escribiendo usuario
        //  🙈 contraseña · 🎉 login OK
        //  😬 login fallido · 😉 clic encima
        // ═══════════════════════════════════════════
        const face  = document.getElementById("emojiFace");
        const lUser = document.getElementById("lUser");
        const lPass = document.getElementById("lPass");

        if (!face || !lPass) return;

        function setEmoji(e, anim) {
          face.textContent = e;
          if (anim) {
            face.classList.remove("emojiBounce", "emojiShake");
            void face.offsetWidth;
            face.classList.add(anim);
            face.addEventListener("animationend", function h() {
              face.classList.remove(anim);
              face.removeEventListener("animationend", h);
            });
          }
        }

        lUser.addEventListener("focus", function () { setEmoji("🧐"); });
        lUser.addEventListener("blur",  function () { setEmoji("👀"); });
        lUser.addEventListener("input", function () {
          setEmoji(lUser.value.length > 0 ? "🧐" : "👀");
        });

        lPass.addEventListener("focus", function () { setEmoji("🙈"); });
        lPass.addEventListener("blur",  function () { setEmoji("👀"); });

        face.addEventListener("click", function () {
          setEmoji("😉", "emojiBounce");
          setTimeout(function () { setEmoji("👀"); }, 1200);
        });

        window.yetiReact = function (success) {
          if (success) {
            setEmoji("🎉", "emojiBounce");
          } else {
            setEmoji("😬", "emojiShake");
            setTimeout(function () { setEmoji("😅"); }, 600);
            setTimeout(function () { setEmoji("👀"); }, 2200);
          }
        };
      });

    </script>
  </body>
</html>