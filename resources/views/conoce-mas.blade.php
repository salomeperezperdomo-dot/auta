<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conoce más · {{ config('institucion.nombre_corto') }}</title>
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
      /* Esta página es independiente del sistema de pestañas (goSection) de
         index.blade.php, así que estas clases no necesitan .active para
         mostrarse — solo reutilizan el mismo diseño visual. */
      body {
        background: #0b1b33;
        font-family: "DM Sans", Calibri, Arial, sans-serif;
        margin: 0;
      }
      .cm-header {
        padding: 1.2rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .cm-back {
        color: #9aa5b1;
        text-decoration: none;
        font-weight: 600;
      }
      .cm-back:hover { color: #ffffff; }
      .cm-logo {
        color: #ffffff;
        font-family: "Sora", sans-serif;
        font-weight: 800;
        font-size: 1.4rem;
      }
      .cm-wrap {
        max-width: 1100px;
        margin: 0 auto;
        padding: 1rem 2rem 4rem;
      }
    </style>
  </head>
  <body>
    <div class="cm-header">
      <span class="cm-logo">AUTA</span>
      <a class="cm-back" href="{{ url('/') }}"><i class="fas fa-arrow-left"></i> Volver al inicio</a>
    </div>

    <div class="cm-wrap">
      <div class="sec-inner">
        <h2 class="sec-h">
          <i
            class="fas fa-play-circle"
            style="color: var(--blue-light); margin-right: 0.5rem"
          ></i
          >Conoce más sobre AUTA
        </h2>
        <p class="sec-sub">Video, datos y un pequeño juego sobre puntualidad</p>

        <div class="cards-grid">
          <!-- 1. Video de YouTube (demo de AUTA) -->
          <div class="info-card">
            <div class="card-ico ico-bl"><i class="fas fa-video"></i></div>
            <h4>Video demostrativo</h4>
            <p>Un recorrido en video por el funcionamiento real del sistema.</p>
            <div style="position: relative; padding-bottom: 56.25%; height: 0; margin-top: 0.8rem; border-radius: 8px; overflow: hidden;">
              {{-- Reemplazar VIDEO_ID_AQUI por el ID del video de YouTube una vez grabado y subido --}}
              <iframe
                src="https://www.youtube.com/embed/VIDEO_ID_AQUI"
                title="Demo de AUTA"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <!-- 2. Infografía en Canva -->
          <div class="info-card">
            <div class="card-ico ico-gr"><i class="fas fa-chart-pie"></i></div>
            <h4>¿Por qué automatizar la asistencia?</h4>
            <p>Una mirada rápida al problema que resuelve AUTA, con datos reales.</p>
            {{-- Reemplazar por la ruta real de la imagen exportada desde Canva --}}
            <img
              src="{{ asset('images/infografia-ods.png') }}"
              alt="Infografía: por qué automatizar la asistencia escolar"
              style="width: 100%; border-radius: 8px; margin-top: 0.8rem;"
            />
          </div>

          <!-- 3. Mini-juego: "Camino a clase" -->
          <div class="info-card">
            <div class="card-ico ico-pu"><i class="fas fa-gamepad"></i></div>
            <h4>Juego: Camino a clase</h4>
            <p>Salta los obstáculos y llega antes de que suene el timbre.</p>
            <iframe
              src="{{ asset('juegos/camino-a-clase.html') }}"
              title="Mini-juego: Camino a clase"
              style="width: 100%; height: 360px; border: 0; border-radius: 8px; margin-top: 0.8rem;"
            ></iframe>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
