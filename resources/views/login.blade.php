<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
      .btn-doLogin[disabled] { opacity: .6; cursor: not-allowed; }
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

            <!-- Selector de roles: el usuario elige su rol antes de ingresar
             y doLogin() lo manda junto con usuario/contraseña a /api/login -->
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
              <a href="#" class="fgt-lnk" onclick="mostrarAvisoRecuperacion(); return false;">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="alert-err" id="fgtMsg" style="background:rgba(46,111,242,0.12);border-color:#2E6FF2;color:#2E6FF2;">
              <i class="fas fa-info-circle"></i>
              <span>Por ahora este sistema no envía correos de recuperación. Contacta al administrador para restablecer tu contraseña.</span>
            </div>
            <!-- doLogin() ahora valida contra la base de datos real
             (POST /api/login) en vez de credenciales fijas en JS -->
            <button class="btn-doLogin" id="btnLogin" onclick="doLogin()">
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
     Esta vista es solo el login. El panel de administración real
     (con el CRUD conectado a la base de datos) vive aparte, en
     panel.blade.php, y se carga después de iniciar sesión, en /panel.
══════════════════════════════════════════════════════ -->

    <script>
      // ═══════════════════════════════════════════
      //  LÓGICA DEL LOGIN
      //  doLogin() manda usuario + contraseña + rol a POST /api/login.
      //  El backend valida contra la tabla "users" (contraseña hasheada).
      //  Si es correcto, guarda usuario y rol en sessionStorage y
      //  redirige al panel real (/panel).
      // ═══════════════════════════════════════════
      const ROLE_LABELS = {
        admin: "Administrador",
        docente: "Docente",
        estudiante: "Estudiante",
      };
      let currentRole = "admin";

      function selectRole(r) {
        currentRole = r;
        document
          .querySelectorAll(".role-btn")
          .forEach((b) => b.classList.remove("sel"));
        document.getElementById("role-" + r).classList.add("sel");
      }

      function mostrarAvisoRecuperacion() {
        document.getElementById("fgtMsg").classList.toggle("show");
      }

      async function doLogin() {
        const usuario = document.getElementById("lUser").value.trim();
        const contrasena = document.getElementById("lPass").value;
        const err = document.getElementById("loginError");
        const msg = document.getElementById("loginMsg");
        const btn = document.getElementById("btnLogin");

        err.classList.remove("show");

        if (!usuario || !contrasena) {
          msg.textContent = "Ingresa tu usuario y contraseña.";
          err.classList.add("show");
          if (window.yetiReact) yetiReact(false);
          return;
        }

        btn.disabled = true;
        try {
          const res = await fetch("/api/login", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ usuario, contrasena, rol: currentRole }),
          });

          if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            if (res.status === 429) {
              msg.textContent = "Demasiados intentos. Espera un minuto antes de volver a intentar.";
            } else {
              msg.textContent = data.error || "Usuario, contraseña o rol incorrecto.";
            }
            err.classList.add("show");
            if (window.yetiReact) yetiReact(false);
            return;
          }

          const user = await res.json();
          if (window.yetiReact) yetiReact(true);
          sessionStorage.setItem("auta_user", user.usuario);
          sessionStorage.setItem("auta_role", ROLE_LABELS[user.rol] || "Administrador");
          setTimeout(function () { window.location.href = "/panel"; }, 900);
        } catch (e) {
          msg.textContent = "No se pudo conectar con el servidor. Intenta de nuevo.";
          err.classList.add("show");
          if (window.yetiReact) yetiReact(false);
        } finally {
          btn.disabled = false;
        }
      }

      // — Emoji reactivo AUTA —
      // 👀 neutro · 🧐 escribiendo usuario · 🙈 contraseña
      // 🎉 login OK · 😬 login fallido · 😉 clic encima
      window.addEventListener("DOMContentLoaded", function () {
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
