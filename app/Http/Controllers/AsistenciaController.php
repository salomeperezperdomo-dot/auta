<?php
namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsistenciaController extends Controller {

    // Mostrar todas las asistencias (API)
    public function index() {
        $asistencias = Asistencia::orderBy('fecha', 'desc')
                                  ->orderBy('hora', 'desc')
                                  ->get();
        return response()->json($asistencias);
    }

    // Registrar asistencia manual (desde el panel)
    public function store(Request $request) {
        // Si viene código QR, buscar estudiante
        if ($request->codigo) {
            $estudiante = Estudiante::where('codigo', $request->codigo)->first();
            if (!$estudiante) {
                return response()->json(['error' => 'Estudiante no encontrado'], 404);
            }
            $nombre = $estudiante->nombre;
            $grado  = $estudiante->grado;
            $estudiante_id = $estudiante->id;
        } else {
            // Registro manual desde el panel
            $nombre = $request->nombre;
            $grado  = $request->grado;
            $estudiante_id = $request->estudiante_id ?? null;
        }

        $asistencia = Asistencia::create([
            'estudiante_id' => $estudiante_id ?? 1,
            'nombre'  => $nombre,
            'grado'   => $grado,
            'estado'  => $request->estado ?? 'Presente',
            'fecha'   => $request->fecha ?? now()->toDateString(),
            'hora'    => $request->hora  ?? now()->toTimeString(),
        ]);
        
        return response()->json($asistencia);
    }

    // Editar una asistencia
    public function update(Request $request, $id) {
        $asistencia = Asistencia::findOrFail($id);
        $asistencia->update([
            'nombre' => $request->nombre,
            'grado'  => $request->grado,
            'estado' => $request->estado,
            'fecha'  => $request->fecha,
            'hora'   => $request->hora,
        ]);
        return response()->json($asistencia);
    }

    // Eliminar una asistencia
    public function destroy($id) {
        Asistencia::findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }

    // ========== ESCÁNER QR ==========
    
    // Mostrar la vista del escáner
    public function mostrarEscaner() {
        return view('escaner');
    }

    // Registrar asistencia por QR (desde el escáner)
    public function registrarPorQR(Request $request) {
        $codigo = $request->codigo;
        
        // Buscar estudiante por código
        $estudiante = Estudiante::where('codigo', $codigo)->first();
        
        if (!$estudiante) {
            return response()->json([
                'success' => false,
                'mensaje' => '❌ Estudiante no encontrado. Código: ' . $codigo
            ]);
        }
        
        // Verificar si ya registró asistencia hoy
        $hoy = now()->toDateString();
        $existe = Asistencia::where('estudiante_id', $estudiante->id)
                            ->where('fecha', $hoy)
                            ->first();
        
        if ($existe) {
            return response()->json([
                'success' => false,
                'mensaje' => "⚠️ {$estudiante->nombre} ya registró asistencia hoy a las {$existe->hora}"
            ]);
        }
        
        // Determinar estado según la hora (hora límite: 7:0 AM)
        $horaActual = now();
        $horaLimite = now()->setTime(7, 10, 0);
        $estado = $horaActual > $horaLimite ? 'Retardo' : 'Presente';
        
        // Crear registro de asistencia
        $asistencia = Asistencia::create([
            'estudiante_id' => $estudiante->id,
            'nombre' => $estudiante->nombre,
            'grado' => $estudiante->grado,
            'estado' => $estado,
            'fecha' => $hoy,
            'hora' => $horaActual->toTimeString()
        ]);
        
        return response()->json([
            'success' => true,
            'mensaje' => "✅ {$estudiante->nombre} - {$estado} a las " . $horaActual->format('h:i A')
        ]);
    }
}