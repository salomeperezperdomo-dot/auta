<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Escáner QR · AUTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        body {
            background: var(--navy);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 0;
        }

        /* Topbar igual al panel */
        .esc-topbar {
            width: 100%;
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            background: rgba(13, 31, 60, 0.88);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--glass-b);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .esc-topbar-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--blue), var(--accent2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #fff;
        }
        .esc-topbar-title {
            font-family: "Sora", sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #fff;
            flex: 1;
        }
        .esc-topbar-title span {
            color: var(--blue-light);
        }
        .esc-back-btn {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--glass);
            border: 1px solid var(--glass-b);
            border-radius: 10px;
            padding: 0.45rem 1rem;
            color: var(--muted);
            font-size: 0.83rem;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }
        .esc-back-btn:hover {
            color: #fff;
            border-color: rgba(255,255,255,0.25);
        }

        /* Contenido central */
        .esc-content {
            width: 100%;
            max-width: 560px;
            padding: 2rem 1.25rem;
        }

        /* Card del escáner */
        .esc-card {
            background: var(--glass);
            border: 1px solid var(--glass-b);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .esc-card-hd {
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid var(--glass-b);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .esc-card-hd-ic {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(37, 99, 235, 0.2);
            color: var(--blue-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        .esc-card-hd h5 {
            font-family: "Sora", sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #fff;
            margin: 0;
        }
        .esc-card-hd p {
            font-size: 0.75rem;
            color: var(--muted);
            margin: 0;
        }
        .esc-card-bd {
            padding: 1.25rem;
        }

        /* Visor de cámara */
        #reader {
            width: 100%;
            border-radius: 14px;
            overflow: hidden;
            background: #000;
        }
        #reader video {
            border-radius: 14px;
        }

        /* Mensaje de estado */
        .esc-msg {
            margin-top: 1rem;
            padding: 0.85rem 1.1rem;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }
        .esc-msg.waiting {
            background: rgba(37, 99, 235, 0.12);
            border: 1px solid rgba(37, 99, 235, 0.25);
            color: var(--blue-light);
        }
        .esc-msg.success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--accent2);
        }
        .esc-msg.error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }
        .esc-msg.processing {
            background: rgba(245, 158, 11, 0.12);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: var(--accent);
        }

        /* Botones */
        .esc-btn-row {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
        }
        .esc-btn-reiniciar {
            flex: 1;
            padding: 0.65rem;
            border-radius: 11px;
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: var(--accent);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
        .esc-btn-reiniciar:hover {
            background: rgba(245, 158, 11, 0.25);
        }
        .esc-btn-volver {
            flex: 1;
            padding: 0.65rem;
            border-radius: 11px;
            background: var(--glass);
            border: 1px solid var(--glass-b);
            color: var(--muted);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            transition: all 0.2s;
        }
        .esc-btn-volver:hover {
            color: #fff;
            border-color: rgba(255,255,255,0.25);
        }

        /* Card últimos registros */
        .esc-log {
            background: var(--glass);
            border: 1px solid var(--glass-b);
            border-radius: 20px;
            overflow: hidden;
        }
        .esc-log-hd {
            padding: 0.9rem 1.2rem;
            border-bottom: 1px solid var(--glass-b);
            font-family: "Sora", sans-serif;
            font-weight: 700;
            font-size: 0.88rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .esc-log-hd i {
            color: var(--blue-light);
        }
        #log-lista {
            padding: 0.75rem 1.2rem;
            max-height: 220px;
            overflow-y: auto;
        }
        .log-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.55rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 0.82rem;
            color: var(--text);
            animation: fadeUp 0.3s ease both;
        }
        .log-item:last-child { border-bottom: none; }
        .log-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .log-dot.presente { background: var(--accent2); }
        .log-dot.retardo  { background: var(--accent); }
        .log-dot.error    { background: #f87171; }
        .log-hora {
            font-size: 0.72rem;
            color: var(--muted);
            margin-left: auto;
            white-space: nowrap;
        }
        .log-empty {
            text-align: center;
            padding: 1.5rem;
            color: var(--muted);
            font-size: 0.82rem;
        }
        .log-empty i {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 0.4rem;
            opacity: 0.3;
        }

        /* Pill sistema activo */
        .p-pill {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 20px;
            padding: 0.28rem 0.75rem;
            font-size: 0.71rem;
            font-weight: 600;
            color: var(--accent2);
        }

        /* Panel de calibración (solo con ?calibrar=1) */
        .cal-wrap { width: 100%; max-width: 560px; padding: 0 1.25rem 2rem; display: none; }
        .cal-card {
            background: var(--glass);
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 20px;
            padding: 1.1rem 1.25rem;
            color: var(--text);
            font-size: 0.82rem;
        }
        .cal-card h6 {
            font-family: "Sora", sans-serif;
            font-weight: 700;
            color: var(--accent);
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
        }
        .cal-live { font-size: 1.4rem; font-weight: 700; color: #fff; font-family: "Sora", sans-serif; }
        .cal-live small { font-size: 0.72rem; font-weight: 400; color: var(--muted); }
        .cal-radios { display: flex; gap: 0.5rem; flex-wrap: wrap; margin: 0.75rem 0; }
        .cal-radios label {
            flex: 1;
            text-align: center;
            padding: 0.5rem 0.4rem;
            border-radius: 10px;
            border: 1px solid var(--glass-b);
            cursor: pointer;
            color: var(--muted);
            font-weight: 600;
        }
        .cal-radios input { display: none; }
        .cal-radios input:checked + span { color: #fff; }
        .cal-radios label:has(input:checked) { background: rgba(37, 99, 235, 0.25); border-color: var(--blue-light); }
        .cal-table { width: 100%; border-collapse: collapse; margin-top: 0.5rem; }
        .cal-table th, .cal-table td { padding: 0.3rem 0.35rem; border-bottom: 1px solid rgba(255,255,255,0.06); text-align: right; }
        .cal-table th:first-child, .cal-table td:first-child { text-align: left; }
        .cal-table th { color: var(--muted); font-weight: 500; }
        .cal-sug { margin-top: 0.75rem; padding: 0.7rem 0.85rem; border-radius: 10px; background: rgba(37, 99, 235, 0.12); border: 1px solid rgba(37, 99, 235, 0.25); }
        .cal-sug.mal { background: rgba(239, 68, 68, 0.12); border-color: rgba(239, 68, 68, 0.3); color: #f87171; }
        .cal-sug.bien { background: rgba(16, 185, 129, 0.12); border-color: rgba(16, 185, 129, 0.3); color: var(--accent2); }
        .cal-btns { display: flex; gap: 0.5rem; margin-top: 0.75rem; }
        .cal-btns button {
            flex: 1; padding: 0.5rem; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 0.8rem;
            background: var(--glass); border: 1px solid var(--glass-b); color: var(--muted);
        }
        .cal-btns button:hover { color: #fff; border-color: rgba(255,255,255,0.25); }
    </style>
</head>
<body>

    <!-- Topbar -->
    <div class="esc-topbar">
        <div class="esc-topbar-logo"><i class="fas fa-qrcode"></i></div>
        <div class="esc-topbar-title">AUTA · <span>Escáner QR</span></div>
        <div class="p-pill">
            <i class="fas fa-circle" style="font-size:0.45rem"></i> Sistema activo
        </div>
        <a href="/panel" class="esc-back-btn">
            <i class="fas fa-arrow-left"></i> Volver al panel
        </a>
    </div>

    <!-- Contenido -->
    <div class="esc-content">

        <!-- Card cámara -->
        <div class="esc-card">
            <div class="esc-card-hd">
                <div class="esc-card-hd-ic"><i class="fas fa-camera"></i></div>
                <div>
                    <h5>Cámara activa</h5>
                    <p>Coloca el carnet QR frente a la cámara</p>
                </div>
            </div>
            <div class="esc-card-bd">
                <div id="reader"></div>

                <div id="mensaje" class="esc-msg waiting">
                    <i class="fas fa-search"></i> Esperando escaneo...
                </div>

                <div class="esc-btn-row">
                    <button class="esc-btn-reiniciar" onclick="reiniciarEscaner()">
                        <i class="fas fa-sync-alt"></i> Reiniciar cámara
                    </button>
                    <a href="/panel" class="esc-btn-volver">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Card log de registros -->
        <div class="esc-log">
            <div class="esc-log-hd">
                <i class="fas fa-history"></i> Registros de esta sesión
            </div>
            <div id="log-lista">
                <div class="log-empty">
                    <i class="fas fa-inbox"></i>
                    Aún no hay registros en esta sesión
                </div>
            </div>
        </div>

    </div>

    <!-- Panel de calibración: solo visible con /escaner?calibrar=1 -->
    <div class="cal-wrap" id="cal-wrap">
        <div class="cal-card">
            <h6><i class="fas fa-sliders-h"></i> Modo calibración (no registra asistencia)</h6>
            <div class="cal-live" id="cal-live">—<small> esperando un QR…</small></div>
            <div class="cal-radios">
                <label><input type="radio" name="cal-clase" value="ninguna" checked><span>No grabar</span></label>
                <label><input type="radio" name="cal-clase" value="fisico"><span>Grabar FÍSICO</span></label>
                <label><input type="radio" name="cal-clase" value="pantalla"><span>Grabar PANTALLA</span></label>
            </div>
            <table class="cal-table" id="cal-tabla"></table>
            <div class="cal-sug" id="cal-sug">Elige una clase y muestra el QR frente a la cámara unos segundos.</div>
            <div class="cal-btns">
                <button type="button" onclick="calCopiar()"><i class="fas fa-copy"></i> Copiar resultados</button>
                <button type="button" onclick="calLimpiar()"><i class="fas fa-trash"></i> Borrar muestras</button>
            </div>
        </div>
    </div>

    <script>
        let scannerActivo = true;
        let html5QrCode;
        let logItems = [];

        // ==========================================================
        // FILTRO DE CALIDAD — detección heurística de "QR mostrado
        // desde una pantalla" (celular, computador, foto en pantalla)
        // ==========================================================
        // Activa/desactiva el filtro fácilmente. Si durante los ensayos
        // el carnet físico real se llega a rechazar por error, poner
        // esto en false mientras se recalibran los umbrales de abajo.
        const FILTRO_CALIDAD_ACTIVO = true;

        // Poner en true mientras se calibra: muestra en la consola del
        // navegador (F12 → Console) los valores medidos en cada intento,
        // para poder ajustar los umbrales con el carnet y un celular reales.
        // Se activa solo desde la URL, para no dejarlo prendido en la demo:
        //   /escaner?debug=1     -> imprime los valores en la consola
        //   /escaner?calibrar=1  -> panel de calibración en pantalla; en este
        //                           modo NO se registra asistencia ni auditoría.
        const PARAMS_URL = new URLSearchParams(window.location.search);
        const MODO_CALIBRACION = PARAMS_URL.get('calibrar') === '1';
        const FILTRO_CALIDAD_DEBUG = MODO_CALIBRACION || PARAMS_URL.get('debug') === '1';

        // Umbrales de partida — NO están garantizados para su cámara/carnet
        // específicos. Hay que calibrarlos así: activar FILTRO_CALIDAD_DEBUG,
        // escanear el carnet físico varias veces y anotar los valores de
        // varianzaLap y difColorProm que salen en consola; luego escanear el
        // mismo QR mostrado en la pantalla de un celular y anotar esos valores.
        // Los umbrales deben quedar en un punto intermedio que nunca rechace
        // el carnet físico real, pero sí distinga la pantalla.
        //
        // Calibrado 2026-09-08 con 5+5 pruebas reales del equipo, mismas
        // condiciones de mesa/luz:
        //   - Carnet físico: 4.63, 7.69, 5.82, 5.74, 13.17
        //   - Celular/pantalla: 15.64, 16.92, 17.70, 17.79, 33.22
        // Hay separación, pero el margen es angosto (solo ~2.5 entre el
        // físico más alto y el celular más bajo) — por eso el umbral solo
        // no basta. Se agregó además una confirmación de 2 lecturas
        // seguidas antes de rechazar (ver más abajo), para que una sola
        // foto ruidosa del carnet real no lo tumbe.
        const UMBRAL_VARIANZA_LAPLACIANA = 4000;
        const UMBRAL_DIFERENCIA_COLOR = 14.5;
        // Métrica B (croma residual, compensa el tinte de la luz). Su umbral es
        // PROVISIONAL: no se usa hasta calibrarlo con ?calibrar=1 y cambiar
        // METRICA_FILTRO a 'B'.
        const UMBRAL_CROMA_RESIDUAL = 6.0;
        // 'A' = color promedio (la calibración original) · 'B' = croma residual
        const METRICA_FILTRO = 'A';

        // Analiza la región central del video (donde debe estar el carnet)
        // buscando dos señales típicas de una pantalla capturada de cerca
        // por otra cámara, y que un carnet impreso normalmente no tiene:
        //
        // 1) Varianza de alta frecuencia (tipo filtro Laplaciano): la
        //    rejilla de subpíxeles y el refresco de una pantalla generan
        //    "ruido" fino (parecido al patrón de muaré) que una superficie
        //    impresa y enfocada normalmente no produce en la misma medida.
        //
        // 2) "Fringing" de color: una pantalla arma el blanco combinando
        //    subpíxeles rojo, verde y azul por separado, así que en zonas
        //    que deberían verse neutras (blancos y negros del QR) aparecen
        //    pequeñas diferencias de color entre canales. El papel o PVC
        //    impreso no tiene ese efecto.
        //
        // Esto es una heurística, no una prueba criminalística: puede
        // fallar con cámaras de baja calidad, poca luz, o carnets muy
        // desgastados. Por eso existen los umbrales calibrables arriba.
        function analizarCalidadImagen(video) {
            try {
                const size = 240; // igual al qrbox configurado abajo
                const vw = video.videoWidth, vh = video.videoHeight;
                if (!vw || !vh) return { pantalla: false, motivo: 'sin datos de video' };

                const cw = Math.min(size, vw), ch = Math.min(size, vh);
                const sx = Math.floor((vw - cw) / 2), sy = Math.floor((vh - ch) / 2);

                const canvas = document.createElement('canvas');
                canvas.width = cw; canvas.height = ch;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, sx, sy, cw, ch, 0, 0, cw, ch);
                const { data } = ctx.getImageData(0, 0, cw, ch);

                // Luminancia en escala de grises
                const gris = new Float32Array(cw * ch);
                for (let i = 0, p = 0; i < data.length; i += 4, p++) {
                    gris[p] = 0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2];
                }

                // 1) Varianza Laplaciana (alta frecuencia / textura fina)
                let sumaLap = 0, sumaLap2 = 0, n = 0;
                for (let y = 1; y < ch - 1; y++) {
                    for (let x = 1; x < cw - 1; x++) {
                        const idx = y * cw + x;
                        const lap = 4 * gris[idx] - gris[idx - 1] - gris[idx + 1] - gris[idx - cw] - gris[idx + cw];
                        sumaLap += lap; sumaLap2 += lap * lap; n++;
                    }
                }
                const mediaLap = sumaLap / n;
                const varianzaLap = (sumaLap2 / n) - (mediaLap * mediaLap);

                // 2) Diferencia de color entre canales (fringing de subpíxeles)
                let sumaDifColor = 0;
                for (let i = 0; i < data.length; i += 4) {
                    const r = data[i], g = data[i + 1], b = data[i + 2];
                    sumaDifColor += Math.abs(r - g) + Math.abs(g - b) + Math.abs(r - b);
                }
                const difColorProm = sumaDifColor / (data.length / 4);

                // 3) Métrica B: "croma residual". La métrica A promedia TODO el
                // recorte, así que sube con una luz cálida (el papel blanco se ve
                // amarillento) o con las zonas de color del carnet, aunque no sea
                // una pantalla. Esta versión usa solo píxeles casi negros o casi
                // blancos (los módulos del QR), agrupados por separado, y le
                // resta a cada grupo su tinte medio. Lo que queda es el "ruido
                // de color" fino que mete una pantalla, sin importar la luz.
                const nGrupo = [0, 0], sumRG = [0, 0], sumBG = [0, 0];
                const grupoPx = new Int8Array(cw * ch);
                for (let i = 0, p = 0; i < data.length; i += 4, p++) {
                    const lum = gris[p];
                    const gr = lum < 70 ? 0 : (lum > 170 ? 1 : -1);
                    grupoPx[p] = gr;
                    if (gr >= 0) {
                        nGrupo[gr]++;
                        sumRG[gr] += data[i] - data[i + 1];
                        sumBG[gr] += data[i + 2] - data[i + 1];
                    }
                }
                let sumaResid = 0, nResid = 0;
                for (let i = 0, p = 0; i < data.length; i += 4, p++) {
                    const gr = grupoPx[p];
                    if (gr < 0) continue;
                    const mrg = sumRG[gr] / nGrupo[gr], mbg = sumBG[gr] / nGrupo[gr];
                    sumaResid += Math.abs((data[i] - data[i + 1]) - mrg) + Math.abs((data[i + 2] - data[i + 1]) - mbg);
                    nResid++;
                }
                const cromaResidual = nResid > 500 ? sumaResid / nResid : null;

                // La decisión usa la métrica elegida en METRICA_FILTRO.
                const valorFiltro = (METRICA_FILTRO === 'B') ? cromaResidual : difColorProm;
                const umbralFiltro = (METRICA_FILTRO === 'B') ? UMBRAL_CROMA_RESIDUAL : UMBRAL_DIFERENCIA_COLOR;
                if (valorFiltro === null) {
                    return { pantalla: false, motivo: 'pocos píxeles neutros', difColorProm, cromaResidual };
                }
                const sospechaPantalla = valorFiltro > umbralFiltro;

                if (FILTRO_CALIDAD_DEBUG) {
                    console.log(
                        `[Filtro de calidad] A(color prom)=${difColorProm.toFixed(2)} (umbral ${UMBRAL_DIFERENCIA_COLOR}) · ` +
                        `B(croma residual)=${cromaResidual === null ? 'n/d' : cromaResidual.toFixed(2)} (umbral ${UMBRAL_CROMA_RESIDUAL}) · ` +
                        `métrica activa: ${METRICA_FILTRO} · ¿pantalla? ${sospechaPantalla}`
                    );
                }

                return {
                    pantalla: sospechaPantalla, valor: valorFiltro, umbral: umbralFiltro,
                    varianzaLap, difColorProm, cromaResidual
                };
            } catch (e) {
                // Si el análisis falla por cualquier razón técnica, no bloquear
                // el registro real por un error de este filtro adicional.
                if (FILTRO_CALIDAD_DEBUG) console.warn('[Filtro de calidad] error:', e.message);
                return { pantalla: false, motivo: 'error: ' + e.message };
            }
        }

        // ---------------- Panel de calibración ----------------
        const calMuestras = { fisico: [], pantalla: [] };
        const CAL_METRICAS = [
            { id: 'A', nombre: 'A · color promedio', umbral: () => UMBRAL_DIFERENCIA_COLOR },
            { id: 'B', nombre: 'B · croma residual', umbral: () => UMBRAL_CROMA_RESIDUAL }
        ];

        function calPercentil(arr, p) {
            if (!arr.length) return null;
            const o = [...arr].sort((a, b) => a - b);
            const i = (o.length - 1) * p, lo = Math.floor(i), hi = Math.ceil(i);
            return o[lo] + (o[hi] - o[lo]) * (i - lo);
        }
        const calFmt = v => (v === null || v === undefined) ? '—' : v.toFixed(2);

        function calMuestra(med) {
            if (typeof med.difColorProm !== 'number' || typeof med.cromaResidual !== 'number') return;
            const clase = (document.querySelector('input[name="cal-clase"]:checked') || {}).value;
            if (clase === 'fisico' || clase === 'pantalla') {
                calMuestras[clase].push({ A: med.difColorProm, B: med.cromaResidual });
            }
            document.getElementById('cal-live').innerHTML =
                `A=${med.difColorProm.toFixed(2)} · B=${med.cromaResidual.toFixed(2)}` +
                `<small> · filtro activo: métrica ${METRICA_FILTRO}</small>`;
            calRender();
        }

        function calStats(arr, umbral, esperaPantalla) {
            const mal = arr.filter(v => esperaPantalla ? v <= umbral : v > umbral).length;
            return {
                n: arr.length,
                min: arr.length ? Math.min(...arr) : null,
                p5: calPercentil(arr, 0.05), med: calPercentil(arr, 0.5), p95: calPercentil(arr, 0.95),
                max: arr.length ? Math.max(...arr) : null,
                pctMal: arr.length ? (100 * mal / arr.length) : null
            };
        }

        function calAnalisis(m) {
            const vf = calMuestras.fisico.map(x => x[m.id]);
            const vp = calMuestras.pantalla.map(x => x[m.id]);
            const f = calStats(vf, m.umbral(), false);
            const p = calStats(vp, m.umbral(), true);
            let margen = null, sugerido = null;
            if (f.n >= 10 && p.n >= 10) {
                margen = (p.p5 - f.p95) / p.p5;      // > 0 = se separan
                sugerido = (f.p95 + p.p5) / 2;
            }
            return { m, f, p, margen, sugerido };
        }

        function calRender() {
            const res = CAL_METRICAS.map(calAnalisis);
            const fila = (nom, s) => `<tr><td>${nom}</td><td>${s.n}</td><td>${calFmt(s.min)}</td>` +
                `<td>${calFmt(s.med)}</td><td>${calFmt(s.max)}</td>` +
                `<td>${s.pctMal === null ? '—' : s.pctMal.toFixed(0) + '%'}</td></tr>`;
            document.getElementById('cal-tabla').innerHTML =
                `<tr><th>Métrica / clase</th><th>n</th><th>mín</th><th>mediana</th><th>máx</th><th>mal*</th></tr>` +
                res.map(r => `<tr><td colspan="6" style="text-align:left;color:#fff;font-weight:600">${r.m.nombre}` +
                    ` (umbral ${r.m.umbral()})</td></tr>` + fila('&nbsp;&nbsp;Físico', r.f) + fila('&nbsp;&nbsp;Pantalla', r.p)).join('') +
                `<tr><td colspan="6" style="text-align:left;color:var(--muted)">*mal = % de lecturas del lado equivocado del umbral actual</td></tr>`;

            const sug = document.getElementById('cal-sug');
            const listas = res.filter(r => r.margen !== null);
            if (!listas.length) {
                sug.className = 'cal-sug';
                sug.textContent = 'Graba al menos 10 lecturas de FÍSICO y 10 de PANTALLA para obtener un umbral sugerido.';
                return;
            }
            const buenas = listas.filter(r => r.margen > 0).sort((a, b) => b.margen - a.margen);
            if (buenas.length) {
                const b = buenas[0];
                sug.className = 'cal-sug bien';
                sug.innerHTML = `Mejor separación: <b>métrica ${b.m.id}</b> (margen ${(b.margen * 100).toFixed(0)}%). ` +
                    `Pon METRICA_FILTRO = '${b.m.id}' y su umbral en <b>${b.sugerido.toFixed(1)}</b>.`;
            } else {
                sug.className = 'cal-sug mal';
                sug.innerHTML = 'En ninguna métrica se separan físico y pantalla con estas condiciones (se solapan). ' +
                    'Prueba otra luz, distancia o brillo del celular, y vuelve a medir.';
            }
        }

        function calTexto() {
            return CAL_METRICAS.map(calAnalisis).map(r => {
                const l = (n, s) => `  ${n}: n=${s.n} min=${calFmt(s.min)} p5=${calFmt(s.p5)} mediana=${calFmt(s.med)} ` +
                    `p95=${calFmt(s.p95)} max=${calFmt(s.max)} mal=${s.pctMal === null ? '—' : s.pctMal.toFixed(0) + '%'}`;
                return `Métrica ${r.m.id} (umbral ${r.m.umbral()})\n${l('FISICO', r.f)}\n${l('PANTALLA', r.p)}` +
                    (r.margen !== null ? `\n  margen=${(r.margen * 100).toFixed(0)}% sugerido=${r.sugerido.toFixed(1)}` : '');
            }).join('\n');
        }

        function calCopiar() {
            const t = calTexto();
            (navigator.clipboard ? navigator.clipboard.writeText(t) : Promise.reject())
                .then(() => alert('Resultados copiados'))
                .catch(() => prompt('Copia estos resultados:', t));
        }

        function calLimpiar() {
            calMuestras.fisico = []; calMuestras.pantalla = [];
            calRender();
        }

        if (MODO_CALIBRACION) {
            document.getElementById('cal-wrap').style.display = 'block';
            calRender();
        }

        
        function iniciarEscaner() {
            html5QrCode = new Html5Qrcode("reader");

            // Buffer de lecturas del mismo código para decidir con la mediana.
            let bufferCodigo = null;
            let bufferMedidas = [];
            let bufferUltimaHora = 0;
            const LECTURAS_PARA_DECIDIR = 3;   // ~0.3 s a 10 fps
            const VENTANA_MAX_MS = 1500;       // si pasa más tiempo, se empieza de nuevo

            Html5Qrcode.getCameras().then(dispositivos => {
                if (dispositivos && dispositivos.length > 0) {
                    html5QrCode.start(
                        dispositivos[0].id,
                        { fps: 10, qrbox: 240 },
                        (codigo) => {
                            // Modo calibración: solo mide y muestra; nunca registra.
                            if (MODO_CALIBRACION) {
                                const videoCal = document.querySelector('#reader video');
                                const med = videoCal ? analizarCalidadImagen(videoCal) : null;
                                if (med) calMuestra(med);
                                return;
                            }

                            if (!scannerActivo) return;

                            if (FILTRO_CALIDAD_ACTIVO) {
                                const video = document.querySelector('#reader video');
                                const analisis = video ? analizarCalidadImagen(video) : null;

                                // Si el análisis falla o no hay datos, no se bloquea el registro.
                                if (analisis && typeof analisis.valor === 'number') {
                                    const ahora = Date.now();
                                    if (codigo !== bufferCodigo || ahora - bufferUltimaHora > VENTANA_MAX_MS) {
                                        bufferCodigo = codigo;
                                        bufferMedidas = [];
                                    }
                                    bufferUltimaHora = ahora;
                                    bufferMedidas.push(analisis.valor);

                                    // Se espera a juntar varias lecturas del MISMO código y se
                                    // decide con la mediana: una foto ruidosa suelta ya no puede
                                    // tumbar al carnet real, ni dejar pasar una pantalla.
                                    if (bufferMedidas.length < LECTURAS_PARA_DECIDIR) return;

                                    const mediana = calPercentil(bufferMedidas, 0.5);
                                    bufferCodigo = null;
                                    bufferMedidas = [];

                                    if (FILTRO_CALIDAD_DEBUG) {
                                        console.log(`[Filtro de calidad] mediana de ${LECTURAS_PARA_DECIDIR} lecturas = ` +
                                            `${mediana.toFixed(2)} (umbral ${analisis.umbral})`);
                                    }

                                    if (mediana > analisis.umbral) {
                                        scannerActivo = false;
                                        mostrarMensaje(
                                            'Código rechazado: parece estar mostrado desde una pantalla. Usa el carnet físico.',
                                            'error', 'fa-mobile-alt'
                                        );
                                        agregarLog('Rechazado — QR mostrado desde una pantalla', 'error');

                                        // Deja registro en la auditoría (tabla intentos_asistencia),
                                        // aunque este intento nunca llegue a /registrar-asistencia.
                                        fetch('{{ url("/registrar-intento-sospechoso") }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                            },
                                            body: JSON.stringify({ codigo: codigo })
                                        }).catch(() => {}); // si falla la auditoría, no interrumpir al usuario

                                        setTimeout(() => {
                                            scannerActivo = true;
                                            mostrarMensaje('Esperando escaneo...', 'waiting', 'fa-search');
                                        }, 2000);
                                        return;
                                    }
                                }
                            }

                            registrarAsistencia(codigo);
                        }
                    );
                } else {
                    mostrarMensaje("No se encontró cámara disponible", "error", "fa-exclamation-triangle");
                }
            }).catch(() => {
                mostrarMensaje("Error al acceder a la cámara", "error", "fa-exclamation-triangle");
            });

        }

        function registrarAsistencia(codigo) {
            scannerActivo = false;
            mostrarMensaje("Procesando carnet...", "processing", "fa-spinner fa-spin");

            fetch('{{ url("/registrar-asistencia") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ codigo: codigo })
            })
            .then(r => r.json())
            .then(datos => {
                const tipo = datos.success ? "success" : "error";
                const icono = datos.success ? "fa-check-circle" : "fa-exclamation-triangle";
                mostrarMensaje(datos.mensaje, tipo, icono);
                agregarLog(datos.mensaje, datos.success ? "presente" : "error");
                setTimeout(() => {
                    scannerActivo = true;
                    mostrarMensaje("Esperando escaneo...", "waiting", "fa-search");
                }, 2500);
            })
            .catch(() => {
                mostrarMensaje("Error de conexión con el servidor", "error", "fa-wifi");
                setTimeout(() => { scannerActivo = true; }, 2000);
            });
        }

        function reiniciarEscaner() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(() => iniciarEscaner());
            } else {
                iniciarEscaner();
            }
        }

        function mostrarMensaje(texto, tipo, icono) {
            const div = document.getElementById('mensaje');
            div.className = `esc-msg ${tipo}`;
            div.innerHTML = `<i class="fas ${icono}"></i> ${texto}`;
        }

        function agregarLog(texto, tipo) {
            const now = new Date();
            const hora = now.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            logItems.unshift({ texto, tipo, hora });

            const lista = document.getElementById('log-lista');
            lista.innerHTML = logItems.map(item => `
                <div class="log-item">
                    <div class="log-dot ${item.tipo}"></div>
                    <span>${item.texto}</span>
                    <span class="log-hora">${item.hora}</span>
                </div>
            `).join('');
        }

        iniciarEscaner();
    </script>
</body>
</html>