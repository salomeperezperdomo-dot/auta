<?php
namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Horario;
use App\Models\IntentoAsistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsistenciaController extends Controller {

    // Mostrar las asistencias (API).
    // Si quien pregunta es un estudiante, SOLO ve sus propios registros —
    // el filtro pasa aquí, en el servidor, no en el navegador. Antes, el
    // navegador recibía la asistencia de todos los estudiantes y solo
    // escondía visualmente lo que no correspondía, lo cual no protegía nada.
    public function index() {
        $query = Asistencia::orderBy('fecha', 'desc')->orderBy('hora', 'desc');

        $usuario = Auth::user();
        if ($usuario && $usuario->rol === 'estudiante') {
            // Si la cuenta de estudiante no está vinculada a ningún
            // estudiante real todavía, no se le muestra nada (mejor eso
            // que mostrar todo por accidente).
            $query->where('estudiante_id', $usuario->estudiante_id ?? 0);
        }
        // El docente ve todos los registros, sin restricción por grado —
        // es la política intencional del proyecto, no un descuido.

        return response()->json($query->get());
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
            $grado  = $estudiante->grado->nombre;
            $estudiante_id = $estudiante->id;
        } else {
            // Registro manual desde el panel: el estudiante_id es obligatorio
            // (antes se caía en un valor por defecto de 1 cuando faltaba, lo
            // que asignaba registros al estudiante equivocado en silencio).
            $data = $request->validate([
                'estudiante_id' => 'required|exists:estudiantes,id',
                'nombre'        => 'required|string|max:100',
                'grado'         => 'required|string|max:10',
                'estado'        => 'nullable|in:Presente,Ausente,Retardo',
                'fecha'         => 'nullable|date',
                'hora'          => 'nullable|date_format:H:i,H:i:s',
            ]);

            $nombre        = $data['nombre'];
            $grado         = $data['grado'];
            $estudiante_id = $data['estudiante_id'];
        }

        $asistencia = Asistencia::create([
            'estudiante_id' => $estudiante_id,
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
            IntentoAsistencia::create([
                'codigo' => $codigo,
                'estudiante_id' => null,
                'resultado' => 'no_encontrado',
                'fecha' => now()->toDateString(),
                'hora' => now()->toTimeString(),
            ]);

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
            IntentoAsistencia::create([
                'codigo' => $codigo,
                'estudiante_id' => $estudiante->id,
                'resultado' => 'duplicado',
                'fecha' => $hoy,
                'hora' => now()->toTimeString(),
            ]);

            return response()->json([
                'success' => false,
                'mensaje' => "⚠️ {$estudiante->nombre} ya registró asistencia hoy a las {$existe->hora}"
            ]);
        }

        // Determinar estado según la hora límite configurada para el
        // grado del estudiante (tabla horarios). Antes este valor estaba
        // fijo en el código (7:10 a.m.) para todos los grados por igual.
        $horaActual = now();
        $horario = Horario::where('grado_id', $estudiante->grado_id)->first();

        if ($horario && $horario->hora_limite) {
            $horaLimite = Carbon::parse($hoy . ' ' . $horario->hora_limite);
        } else {
            // Si ese grado todavía no tiene un horario configurado,
            // usamos 7:10 a.m. como valor por defecto razonable, en vez
            // de fallar o dejar pasar el registro sin ningún control.
            $horaLimite = Carbon::parse($hoy . ' 07:10:00');
        }

        $estado = $horaActual->gt($horaLimite) ? 'Retardo' : 'Presente';

        // Crear registro de asistencia
        $asistencia = Asistencia::create([
            'estudiante_id' => $estudiante->id,
            'nombre' => $estudiante->nombre,
            'grado' => $estudiante->grado->nombre,
            'estado' => $estado,
            'fecha' => $hoy,
            'hora' => $horaActual->toTimeString()
        ]);

        return response()->json([
            'success' => true,
            'mensaje' => "✅ {$estudiante->nombre} - {$estado} a las " . $horaActual->format('h:i A')
        ]);
    }

    // Recibe el aviso del filtro de calidad del escáner (JS) cuando
    // rechaza un código por parecer mostrado desde una pantalla, y lo
    // guarda en la auditoría — así queda registro aunque el intento
    // nunca haya llegado a registrarPorQR().
    public function registrarIntentoSospechoso(Request $request) {
        $codigo = $request->codigo;
        $estudiante = Estudiante::where('codigo', $codigo)->first();

        IntentoAsistencia::create([
            'codigo' => $codigo ?? '(sin código)',
            'estudiante_id' => $estudiante?->id,
            'resultado' => 'pantalla_rechazada',
            'fecha' => now()->toDateString(),
            'hora' => now()->toTimeString(),
        ]);

        return response()->json(['ok' => true]);
    }
}
