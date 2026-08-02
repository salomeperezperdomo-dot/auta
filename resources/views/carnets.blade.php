<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Carnets · AUTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 15px 25px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header-actions h2 {
            margin: 0;
            color: #1e293b;
        }
        .btn-imprimir {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            padding: 10px 25px;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            margin-right: 10px;
        }
        .btn-volver {
            background: #64748b;
            border: none;
            padding: 10px 25px;
            border-radius: 12px;
            color: white;
            text-decoration: none;
        }
        .card-carnet {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 24px;
            padding: 25px;
            color: white;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        .card-carnet:hover {
            transform: translateY(-5px);
        }
        .qr-code {
            background: white;
            padding: 10px;
            border-radius: 16px;
            display: inline-block;
            margin: 10px auto;
        }
        .codigo-texto {
            background: rgba(255,255,255,0.1);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
        }
        hr {
            background: rgba(255,255,255,0.2);
            margin: 15px 0;
        }
        @media print {
            .no-print { display: none !important; }
            .card-carnet { page-break-inside: avoid; margin: 10px; }
            body { background: white; padding: 0; }
            .container { max-width: 100%; }
        }
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: none;
            border-radius: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Botones de acción (no se imprimen) -->
        <div class="header-actions no-print">
            <h2><i class="fas fa-id-card"></i> Generador de Carnets QR</h2>
            <div>
                <button class="btn-imprimir" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir carnets
                </button>
                <a href="/panel" class="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <!-- Grid de carnets -->
        <div class="row" id="carnets-container">
            @foreach($estudiantes as $estudiante)
            <div class="col-md-4">
                <div class="card-carnet">
                    <div class="text-center">
                        <i class="fas fa-school" style="font-size: 45px; color: #60a5fa;"></i>
                        <h4 class="mt-2" style="font-weight: bold;">I.E. San José</h4>
                        <h5 class="mt-3">{{ $estudiante->nombre }}</h5>
                        <p style="color: #94a3b8 !important; margin: 5px 0;">Grado: {{ $estudiante->grado->nombre }}</p>
                        
                        <!-- Aquí se genera el QR -->
                        <div id="qr-{{ $estudiante->id }}" class="qr-code mx-auto"></div>
                        
                        <p class="codigo-texto mt-2">
                            <i class="fas fa-qrcode"></i> Código: {{ $estudiante->codigo }}
                        </p>
                        <hr>
                        <small style="color: #94a3b8;">
                            <i class="fas fa-info-circle"></i> Presenta este carnet para registrar tu asistencia
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Mensaje si no hay estudiantes -->
        @if($estudiantes->isEmpty())
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle"></i> No hay estudiantes registrados.
            <a href="/panel" style="color: #92400e; font-weight: bold;">Crea estudiantes desde el panel</a>
        </div>
        @endif
    </div>

    <script>
        // Generar QR para cada estudiante
        @foreach($estudiantes as $estudiante)
        new QRCode(document.getElementById("qr-{{ $estudiante->id }}"), {
            text: "{{ $estudiante->codigo }}",
            width: 140,
            height: 140,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        @endforeach
    </script>
</body>
</html>