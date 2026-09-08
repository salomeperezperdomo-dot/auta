<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistema de Asistencia · {{ config('institucion.nombre_corto') }}</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}" />
    <style>
      /* ── PRELOADER ── */
      #preloader {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: var(--navy);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1.25rem;
        transition:
          opacity 0.6s ease,
          visibility 0.6s ease;
      }
      #preloader.oculto {
        opacity: 0;
        visibility: hidden;
      }
      #preloader::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
          radial-gradient(
            ellipse 80% 60% at 20% 10%,
            rgba(37, 99, 235, 0.2) 0%,
            transparent 60%
          ),
          radial-gradient(
            ellipse 60% 50% at 80% 85%,
            rgba(16, 185, 129, 0.12) 0%,
            transparent 55%
          );
      }
      .pre-logo {
        position: relative;
        z-index: 1;
        width: 68px;
        height: 68px;
        border-radius: 20px;
        background: linear-gradient(135deg, #2563eb, #10b981);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #fff;
        box-shadow: 0 8px 32px rgba(37, 99, 235, 0.4);
        animation: logoPulse 1.5s ease-in-out infinite;
      }
      @keyframes logoPulse {
        0%,
        100% {
          transform: scale(1);
          box-shadow: 0 8px 32px rgba(37, 99, 235, 0.4);
        }
        50% {
          transform: scale(1.08);
          box-shadow: 0 12px 48px rgba(37, 99, 235, 0.7);
        }
      }
      .pre-title {
        position: relative;
        z-index: 1;
        text-align: center;
        font-family: "Sora", sans-serif;
        font-weight: 800;
        font-size: 1.1rem;
        color: #fff;
      }
      .pre-title span {
        color: #3b82f6;
      }
      .pre-sub {
        position: relative;
        z-index: 1;
        font-size: 0.76rem;
        color: #94a3b8;
        margin-top: -0.8rem;
        text-align: center;
      }
      .pre-bar-wrap {
        position: relative;
        z-index: 1;
        width: 200px;
        height: 4px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 99px;
        overflow: hidden;
      }
      .pre-bar {
        height: 100%;
        background: linear-gradient(90deg, #2563eb, #10b981);
        border-radius: 99px;
        width: 0%;
        animation: loadBar 1.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
      }
      @keyframes loadBar {
        0% {
          width: 0%;
        }
        40% {
          width: 60%;
        }
        70% {
          width: 82%;
        }
        100% {
          width: 100%;
        }
      }
      .pre-dots {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 0.45rem;
      }
      .pre-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: dotBounce 0.8s ease-in-out infinite;
      }
      .pre-dot:nth-child(1) {
        background: #2563eb;
        animation-delay: 0s;
      }
      .pre-dot:nth-child(2) {
        background: #3b82f6;
        animation-delay: 0.15s;
      }
      .pre-dot:nth-child(3) {
        background: #10b981;
        animation-delay: 0.3s;
      }
      @keyframes dotBounce {
        0%,
        100% {
          transform: translateY(0);
        }
        50% {
          transform: translateY(-8px);
        }
      }
      .pre-status {
        position: relative;
        z-index: 1;
        font-size: 0.74rem;
        color: #94a3b8;
        min-height: 1.1rem;
        text-align: center;
        transition: opacity 0.3s;
      }

      /* ── AUTA LOGO ── */
      #auta-logo-sb {
        font-family: "Sora", sans-serif;
        font-weight: 900;
        font-size: 1rem;
        letter-spacing: 0.12em;
        background: linear-gradient(135deg, #2563eb, #10b981);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        border-radius: 12px;
        border: 1.5px solid rgba(37,99,235,0.4);
      }
      #auta-logo-top {
        font-family: "Sora", sans-serif;
        font-weight: 900;
        letter-spacing: 0.14em;
        font-size: 1.05rem;
        background: linear-gradient(135deg, #60a5fa, #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      .pre-title span {
        font-size: 1.6rem !important;
        letter-spacing: 0.18em;
        font-weight: 900 !important;
      }

      /* ── HERO ACRONYM BADGE ── */
      .hero-acronym-badge {
        display: inline-flex;
        gap: 0.25rem;
        margin-bottom: 1.2rem;
        background: rgba(37,99,235,0.08);
        border: 1px solid rgba(37,99,235,0.2);
        border-radius: 14px;
        padding: 0.55rem 1.1rem;
        backdrop-filter: blur(6px);
      }
      .acro-letter {
        font-family: "Sora", sans-serif;
        font-weight: 900;
        font-size: 1.55rem;
        background: linear-gradient(135deg, #2563eb, #10b981);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
        letter-spacing: 0.06em;
      }

      /* ── HERO SLOGAN ── */
      .hero-slogan {
        font-family: "DM Sans", sans-serif;
        font-style: italic;
        font-size: 1.05rem;
        color: #94a3b8;
        margin-bottom: 1rem;
        margin-top: -0.3rem;
        letter-spacing: 0.01em;
      }
    </style>
  </head>
  <body>
    <!-- ══ PRELOADER ══ -->
    <div id="preloader">
      <div class="pre-logo"><i class="fas fa-qrcode"></i></div>
      <div>
        <div class="pre-title"><span>AUTA</span></div>
      </div>
      <div class="pre-sub">Automatización de la Toma de Asistencia</div>
      <div class="pre-bar-wrap"><div class="pre-bar"></div></div>
      <div class="pre-dots">
        <div class="pre-dot"></div>
        <div class="pre-dot"></div>
        <div class="pre-dot"></div>
      </div>
      <div class="pre-status" id="preStatus">Iniciando sistema...</div>
    </div>

    <!-- Overlay y sidebar hamburguesa (Plantilla Sidebars de Bootstrap) -->
    <div class="site-overlay" id="siteOverlay" onclick="closeSidebar()"></div>
    <aside class="site-sidebar" id="siteSidebar">
      <div class="sb-logo">
        <div class="sb-logo-icon" id="auta-logo-sb">AUTA</div>
      </div>
      <nav class="sb-nav">
        <div class="sb-label">Navegación</div>
        <button
          class="sb-btn active"
          data-sec="inicio"
          onclick="goSection('inicio', this)"
        >
          <span class="sb-icon"><i class="fas fa-home"></i></span>Inicio
        </button>
        <button
          class="sb-btn"
          data-sec="objetivos"
          onclick="goSection('objetivos', this)"
        >
          <span class="sb-icon"><i class="fas fa-bullseye"></i></span>Objetivos
        </button>
        <button
          class="sb-btn"
          data-sec="equipo"
          onclick="goSection('equipo', this)"
        >
          <span class="sb-icon"><i class="fas fa-users"></i></span>Equipo
        </button>
        <button
          class="sb-btn"
          data-sec="sistema"
          onclick="goSection('sistema', this)"
        >
          <span class="sb-icon"><i class="fas fa-cogs"></i></span>Sistema
        </button>
        <button
          class="sb-btn"
          data-sec="contacto"
          onclick="goSection('contacto', this)"
        >
          <span class="sb-icon"><i class="fas fa-envelope"></i></span>Contacto /
          Contact
        </button>
        <div class="sb-label" style="margin-top: 1.3rem">Acceso</div>
        <button class="btn-sec" onclick="window.location.href = '/login'">
          <span
            class="sb-icon"
            style="background: rgba(16, 185, 129, 0.15); color: var(--accent2)"
            ><i class="fas fa-sign-in-alt"></i></span
          >Iniciar Sesión
        </button>
      </nav>
      <div class="sb-footer">
        <a href="mailto:iesanjose@edu.co"
          ><i class="fas fa-envelope"></i> iesanjose@edu.co</a
        >
        <a
          href="https://www.instagram.com/proyecto.asistencia?igsh=eGpqZ2xnMTQ2bXow"
          ><i class="fab fa-instagram"></i> @proyecto.asistencia</a
        >
      </div>
    </aside>

    <!-- Topbar -->
    <header class="site-topbar">
      <button class="ham-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
      </button>
      <div class="site-brand"><span id="auta-logo-top">AUTA</span></div>
      <div class="topbar-pill"><i class="fas fa-circle"></i> En línea</div>
      <button class="btn-login-top" onclick="window.location.href = '/login'">
        <i class="fas fa-sign-in-alt"></i> Ingresar
      </button>
    </header>

    <!-- Contenido del sitio -->
    <div class="site-content">
      <!-- INICIO -->
      <div class="site-section active" id="sec-inicio">
        <div class="hero-wrap">
          <div class="hero-tag">
            <i class="fas fa-bolt"></i> Tecnología Educativa · 2026
          </div>
          <h1 class="hero-title">
            Automatización de la<br /><span class="gradient-text"
              >Toma de Asistencia</span
            >
          </h1>
          <p class="hero-slogan">
            <i class="fas fa-quote-left" style="opacity:.4; font-size:.8em; margin-right:.35rem"></i>Innovando en tecnología educativa para un futuro mejor<i class="fas fa-quote-right" style="opacity:.4; font-size:.8em; margin-left:.35rem"></i>
          </p>
          <p class="hero-sub">
            Solución digital que registra la asistencia mediante lectura de
            códigos QR, eliminando errores manuales y dando control en
            tiempo real.
          </p>
          <div class="hero-ctas">
            <button class="btn-prim" onclick="goSection('sistema', null)">
              <i class="fas fa-cogs"></i> Ver Sistema
            </button>
            <button class="sb-btn" onclick="window.location.href = '/login'">
              <i class="fas fa-sign-in-alt"></i> Acceder al Panel
            </button>
          </div>
          <div class="stats-row">
            <div class="stat-cell">
              <div class="stat-num"><span>0</span> errores</div>
              <div class="stat-lbl">Registros manuales eliminados</div>
            </div>
            <div class="stat-cell">
              <div class="stat-num"><span>100</span>%</div>
              <div class="stat-lbl">Precisión en registros</div>
            </div>
            <div class="stat-cell">
              <div class="stat-num">Tiempo <span>real</span></div>
              <div class="stat-lbl">Consulta inmediata de datos</div>
            </div>
          </div>
        </div>
        <div class="sec-inner" style="padding-top: 0">
          <h2 class="sec-h">¿Cómo funciona?</h2>
          <p class="sec-sub">El proceso completo en 4 pasos</p>
          <div class="flow">
            <div class="flow-step">
              <div class="flow-num">1</div>
              <h5>Carnet</h5>
              <p>Estudiante presenta su carnet con código único</p>
            </div>
            <div class="flow-step">
              <div class="flow-num">2</div>
              <h5>Escaneo</h5>
              <p>El lector captura el código al instante</p>
            </div>
            <div class="flow-step">
              <div class="flow-num">3</div>
              <h5>Registro</h5>
              <p>Se guarda fecha, hora y estado automáticamente</p>
            </div>
            <div class="flow-step">
              <div class="flow-num">4</div>
              <h5>Reporte</h5>
              <p>Datos disponibles en tiempo real</p>
            </div>
          </div>
          <h2 class="sec-h">Características clave</h2>
          <p class="sec-sub">Lo que hace especial a nuestro sistema</p>
          <div class="cards-grid">
            <div class="info-card">
              <div class="card-ico ico-or"><i class="fas fa-bolt"></i></div>
              <h4>Automatización</h4>
              <p>Registro sin intervención manual ni papeles.</p>
            </div>
            <div class="info-card">
              <div class="card-ico ico-bl">
                <i class="fas fa-chart-line"></i>
              </div>
              <h4>Eficiencia</h4>
              <p>Reduce el tiempo dedicado al control de asistencia.</p>
            </div>
            <div class="info-card">
              <div class="card-ico ico-gr"><i class="fas fa-bullseye"></i></div>
              <h4>Precisión</h4>
              <p>Registros exactos de presencias y ausencias.</p>
            </div>
            <div class="info-card">
              <div class="card-ico ico-pu"><i class="fas fa-clock"></i></div>
              <h4>Tiempo Real</h4>
              <p>Consulta inmediata para docentes y admins.</p>
            </div>
          </div>
          <div class="ps-grid">
            <div class="ps-card">
              <div class="ps-hd prob">
                <i class="fas fa-exclamation-triangle"></i> Problemática Actual
              </div>
              <div class="ps-body">
                <p>
                  El proceso manual genera <strong>errores frecuentes</strong> y
                  pérdida de tiempo.
                </p>
                <p>
                  <strong>Consecuencias:</strong> Registros inexactos, tiempo
                  perdido y reportes deficientes.
                </p>
              </div>
            </div>
            <div class="ps-card">
              <div class="ps-hd sol">
                <i class="fas fa-lightbulb"></i> Nuestra Solución
              </div>
              <div class="ps-body">
                <p>
                  <strong>Aplicación digital</strong> vinculada a un lector de
                  códigos de barras.
                </p>
                <p>
                  <strong>Beneficios:</strong> Cero errores manuales, control
                  preciso de asistencias y retardos.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- OBJETIVOS -->
      <div class="site-section" id="sec-objetivos">
        <div class="sec-inner">
          <h2 class="sec-h">
            <i
              class="fas fa-bullseye"
              style="color: var(--blue-light); margin-right: 0.5rem"
            ></i
            >Objetivos del Proyecto
          </h2>
          <p class="sec-sub">Metas que guían el desarrollo del sistema</p>
          <div class="obj-list">
            <div class="obj-item">
              <div class="obj-num">1</div>
              <div class="obj-content">
                <h3>Diseñar e implementar una solución digital</h3>
                <p>
                  Que utilice estrategias de automatización para optimizar la
                  toma de asistencia, el registro de retardos e inasistencias en
                  instituciones educativas.
                </p>
              </div>
            </div>
            <div class="obj-item">
              <div class="obj-num">2</div>
              <div class="obj-content">
                <h3>Automatizar la toma de asistencia</h3>
                <p>
                  Mediante tecnología que identifique a cada estudiante a través
                  de su carnet institucional, eliminando el registro manual.
                </p>
              </div>
            </div>
            <div class="obj-item">
              <div class="obj-num">3</div>
              <div class="obj-content">
                <h3>Diseñar una base de datos centralizada</h3>
                <p>
                  Que almacene los registros de asistencia permitiendo su
                  consulta y análisis en tiempo real por docentes y
                  administradores.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- EQUIPO -->
      <div class="site-section" id="sec-equipo">
        <div class="sec-inner">
          <h2 class="sec-h">
            <i
              class="fas fa-users"
              style="color: var(--blue-light); margin-right: 0.5rem"
            ></i
            >Nuestro Equipo
          </h2>
          <p class="sec-sub">Las personas detrás del proyecto</p>
          <div class="team-grid">
            <div class="team-card">
              <div class="team-av"><i class="fas fa-user-tie"></i></div>
              <h4>Project Owner</h4>
              <p>
                Dirige el proyecto y coordina con clientes internos para
                asegurar requisitos.
              </p>
            </div>
            <div class="team-card">
              <div class="team-av"><i class="fas fa-paint-brush"></i></div>
              <h4>Diseñador UX</h4>
              <p>
                Diseña interfaces intuitivas para registro y visualización de
                reportes.
              </p>
            </div>
            <div class="team-card">
              <div class="team-av"><i class="fas fa-code"></i></div>
              <h4>Desarrollador</h4>
              <p>Programa la funcionalidad del sistema y base de datos.</p>
            </div>
            <div class="team-card">
              <div class="team-av"><i class="fas fa-bullhorn"></i></div>
              <h4>Marketing</h4>
              <p>Gestiona la comunicación interna y adopción del sistema.</p>
            </div>
            <div class="team-card">
              <div class="team-av"><i class="fas fa-vial"></i></div>
              <h4>Testing / QA</h4>
              <p>Valida la precisión del sistema antes del despliegue.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- SISTEMA -->
      <div class="site-section" id="sec-sistema">
        <div class="sec-inner">
          <h2 class="sec-h">
            <i
              class="fas fa-cogs"
              style="color: var(--blue-light); margin-right: 0.5rem"
            ></i
            >Funcionamiento del Sistema
          </h2>
          <p class="sec-sub">Tecnología al servicio de la educación</p>
          <div class="cards-grid">
            <div class="info-card">
              <div class="card-ico ico-bl"><i class="fas fa-barcode"></i></div>
              <h4>Lectura de Códigos</h4>
              <p>Sistema automático integrado a los carnets estudiantiles.</p>
            </div>
            <div class="info-card">
              <div class="card-ico ico-gr"><i class="fas fa-database"></i></div>
              <h4>Base de Datos</h4>
              <p>Almacenamiento seguro y centralizado de registros.</p>
            </div>
            <div class="info-card">
              <div class="card-ico ico-pu">
                <i class="fas fa-chart-bar"></i>
              </div>
              <h4>Reportes Automáticos</h4>
              <p>Generación automática de reportes y estadísticas.</p>
            </div>
            <div class="info-card">
              <div class="card-ico ico-or"><i class="fas fa-bell"></i></div>
              <h4>Notificaciones</h4>
              <p>Alertas automáticas a padres y docentes sobre ausencias.</p>
            </div>
            <div class="info-card">
              <div class="card-ico ico-te">
                <i class="fas fa-shield-alt"></i>
              </div>
              <h4>Seguridad por Roles</h4>
              <p>Acceso diferenciado para admin, docente y estudiante.</p>
            </div>
            <div class="info-card">
              <div class="card-ico ico-re">
                <i class="fas fa-mobile-alt"></i>
              </div>
              <h4>Multi-dispositivo</h4>
              <p>Acceso desde computador, tablet o celular.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- CONTACTO -->
      <div class="site-section" id="sec-contacto">
        <div class="sec-inner">
          <h2 class="sec-h">
            <i
              class="fas fa-envelope"
              style="color: var(--blue-light); margin-right: 0.5rem"
            ></i
            >Contacto
            <span style="color: var(--muted); font-size: 1rem; font-weight: 400"
              >/ Contact</span
            >
          </h2>
          <p class="sec-sub">
            ¿Tienes preguntas? Escríbenos ·
            <em>Have questions? Reach out to us</em>
          </p>
          <div class="contact-grid">
            <div class="contact-info">
              <h2><span class="gradient-text">AUTA</span> · {{ config('institucion.nombre_corto') }}</h2>
              <p class="motto">
                "Innovando en tecnología educativa para un futuro mejor"<br /><em
                  style="font-size: 0.85rem"
                  >"Innovating in educational technology for a better
                  future"</em
                >
              </p>
              <div class="c-detail">
                <i class="fas fa-envelope"></i> iesanjose@edu.co
              </div>
              <div class="c-detail"><i class="fas fa-phone"></i> 2770630</div>
              <div class="c-detail">
                <i class="fab fa-instagram"></i
                ><a
                  href="https://www.instagram.com/proyecto.asistencia?igsh=eGpqZ2xnMTQ2bXow"
                  >@proyecto.asistencia</a
                >
              </div>
            </div>
            <div class="contact-form">
              <h4>
                <i
                  class="fas fa-paper-plane"
                  style="color: var(--blue-light); margin-right: 0.4rem"
                ></i
                >Envíanos un mensaje
                <span
                  style="
                    color: var(--muted);
                    font-size: 0.82rem;
                    font-weight: 400;
                  "
                  >/ Send us a message</span
                >
              </h4>
              <label class="flbl">Tu nombre / <em>Your name</em></label>
              <input type="text" class="finp" placeholder="María González" />
              <label class="flbl"
                >Correo electrónico / <em>Email address</em></label
              >
              <input
                type="email"
                class="finp"
                placeholder="correo@ejemplo.com"
              />
              <label class="flbl">Mensaje / <em>Message</em></label>
              <textarea
                class="finp"
                placeholder="Escribe tu mensaje aquí... / Write your message here..."
              ></textarea>
              <button class="btn-submit">
                <i class="fas fa-paper-plane"></i> Enviar / Send
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-inner">
        <span class="f-copy"
          >© 2026 AUTA · {{ config('institucion.nombre_corto') }} · Todos los derechos reservados</span
        >
        <div class="f-links">
          <a onclick="goSection('inicio', null)">Inicio</a>
          <a onclick="goSection('objetivos', null)">Objetivos</a>
          <a onclick="goSection('equipo', null)">Equipo</a>
          <a onclick="goSection('contacto', null)">Contacto / Contact</a>
        </div>
        <div class="f-socials">
          <a
            href="https://www.instagram.com/proyecto.asistencia?igsh=eGpqZ2xnMTQ2bXow"
            title="Instagram"
            ><i class="fab fa-instagram"></i
          ></a>
          <a href="mailto:iesanjose@edu.co" title="Email"
            ><i class="fas fa-envelope"></i
          ></a>
        </div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/navegacion.js') }}"></script>
    <script>
      /* ── PRELOADER: mensajes de estado y ocultado ── */
      const estados = [
        "Iniciando sistema...",
        "Cargando recursos...",
        "Conectando base de datos...",
        "Verificando sesión...",
        "¡Listo!",
      ];
      const statusEl = document.getElementById("preStatus");
      let idx = 0;
      const statusInterval = setInterval(() => {
        idx++;
        if (idx < estados.length) {
          statusEl.style.opacity = "0";
          setTimeout(() => {
            statusEl.textContent = estados[idx];
            statusEl.style.opacity = "1";
          }, 150);
        } else {
          clearInterval(statusInterval);
        }
      }, 400);

      /* Oculta el preloader al terminar de cargar */
      window.addEventListener("load", () => {
        setTimeout(() => {
          document.getElementById("preloader").classList.add("oculto");
        }, 2000);
      });
    </script>
  </body>
</html>