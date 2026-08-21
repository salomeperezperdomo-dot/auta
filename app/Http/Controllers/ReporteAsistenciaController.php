<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Http\Request;

class ReporteAsistenciaController extends Controller
{
    public function exportarAdminPdf(Request $request)
    {
        // Construir la consulta con los filtros
        $query = Asistencia::with('estudiante');
        
        if ($request->filled('buscar')) {
            $query->whereHas('estudiante', function ($q) use ($request) {
                $q->where('nombre', 'LIKE', '%' . $request->buscar . '%');
            });
        }
        
        if ($request->filled('grado')) {
            $query->where('grado', $request->grado);
        }
        
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }
        
        $asistencias = $query->orderBy('fecha', 'desc')->orderBy('hora', 'desc')->get();
        
        // Obtener resumen de estadísticas
        $total = $asistencias->count();
        $presentes = $asistencias->where('estado', 'Presente')->count();
        $ausentes = $asistencias->where('estado', 'Ausente')->count();
        $retardos = $asistencias->where('estado', 'Retardo')->count();
        
        return Pdf::view('pdfs.reporte-admin', [
            'asistencias' => $asistencias,
            'total' => $total,
            'presentes' => $presentes,
            'ausentes' => $ausentes,
            'retardos' => $retardos,
            'filtros' => [
                'buscar' => $request->buscar,
                'grado' => $request->grado,
                'fecha' => $request->fecha,
            ],
        ])
        ->format('a4')
        ->name('reporte-asistencia-admin-' . now()->format('Y-m-d') . '.pdf')
        ->download();
    }
}