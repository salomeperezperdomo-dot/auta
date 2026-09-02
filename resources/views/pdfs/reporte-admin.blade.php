<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Asistencia - Admin</title>
    <style>
        body { font-family: 'Arial', sans-serif; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { color: #2c3e50; margin: 0; font-size: 22px; }
        .header p { color: #7f8c8d; margin: 4px 0; font-size: 13px; }
        
        .stats-grid { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
        .stat-card { 
            background: #f8f9fa; 
            padding: 10px 18px; 
            border-radius: 8px; 
            border-left: 4px solid #3498db;
            flex: 1;
            min-width: 80px;
        }
        .stat-card .num { font-size: 22px; font-weight: bold; }
        .stat-card .label { font-size: 12px; color: #7f8c8d; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        th { background-color: #3498db; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        .present { color: #27ae60; font-weight: bold; }
        .absent { color: #e74c3c; font-weight: bold; }
        .retardo { color: #f39c12; font-weight: bold; }
        
        .footer { 
            margin-top: 25px; 
            font-size: 11px; 
            color: #95a5a6; 
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 12px;
        }
        .filter-info { 
            background: #f1f3f5; 
            padding: 8px 12px; 
            border-radius: 6px; 
            font-size: 12px; 
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Reporte de Asistencia</h1>
        <p><strong>Institución Educativa San José</strong></p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @if($filtros['buscar'] || $filtros['grado'] || $filtros['fecha'])
    <div class="filter-info">
        <strong>Filtros aplicados:</strong>
        @if($filtros['buscar']) 🔎 {{ $filtros['buscar'] }} @endif
        @if($filtros['grado']) 📚 {{ $filtros['grado'] }} @endif
        @if($filtros['fecha']) 📅 {{ \Carbon\Carbon::parse($filtros['fecha'])->format('d/m/Y') }} @endif
    </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card" style="border-left-color: #3498db;">
            <div class="num">{{ $total }}</div>
            <div class="label">Total registros</div>
        </div>
        <div class="stat-card" style="border-left-color: #27ae60;">
            <div class="num">{{ $presentes }}</div>
            <div class="label">✅ Presentes</div>
        </div>
        <div class="stat-card" style="border-left-color: #e74c3c;">
            <div class="num">{{ $ausentes }}</div>
            <div class="label">❌ Ausentes</div>
        </div>
        <div class="stat-card" style="border-left-color: #f39c12;">
            <div class="num">{{ $retardos }}</div>
            <div class="label">⏰ Retardos</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Estudiante</th>
                <th>Grado</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($asistencias as $index => $a)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $a->estudiante->nombre ?? 'N/A' }}</td>
                <td>{{ $a->grado }}</td>
                <td>{{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($a->hora)->format('h:i A') }}</td>
                <td class="{{ strtolower($a->estado) }}">{{ $a->estado }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No hay registros con los filtros aplicados</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Reporte generado automáticamente por el sistema de asistencia · {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>