<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GradoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\HorarioController;
use App\Models\Estudiante;
use App\Http\Controllers\ReporteAsistenciaController;

// ══════════════════════════════════════════════════════
// RUTAS PÚBLICAS (no requieren haber iniciado sesión)
// ══════════════════════════════════════════════════════
Route::get('/', function () { return view('index'); });
Route::get('/login', function () { return view('login'); })->name('login');
// Máximo 5 intentos de login por minuto (por IP) — protección básica contra
// fuerza bruta. Si se exceden, Laravel responde 429 automáticamente.
Route::post('/api/login', [UsuarioController::class, 'login'])->middleware('throttle:5,1');

// ══════════════════════════════════════════════════════
// RUTAS PROTEGIDAS (requieren sesión iniciada, cualquier rol)
// ══════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {

    Route::post('/api/logout', [UsuarioController::class, 'logout']);
    Route::get('/panel', function () { return view('panel'); });

    // Lectura: admin, docente y estudiante pueden consultar
    Route::get('/api/asistencia', [AsistenciaController::class, 'index']);
    Route::get('/api/estudiantes', [EstudianteController::class, 'index']);
    Route::get('/grados', [GradoController::class, 'index']);
    Route::get('/grados/{grado}', [GradoController::class, 'show']);
    Route::get('/grupos', [GrupoController::class, 'index']);
    Route::get('/grupos/{grupo}', [GrupoController::class, 'show']);
    Route::get('/horarios', [HorarioController::class, 'index']);
    Route::get('/horarios/{horario}', [HorarioController::class, 'show']);

    // ══════════════════════════════════════════════════
    // SOLO ADMIN: gestión completa (crear/editar/eliminar)
    // y las páginas de escáner/carnets, tal como ya lo
    // refleja el menú del panel (solo el admin las ve).
    // ══════════════════════════════════════════════════
    Route::middleware('role:admin')->group(function () {

        Route::get('/escaner', [AsistenciaController::class, 'mostrarEscaner'])->name('escaner');
        Route::post('/registrar-asistencia', [AsistenciaController::class, 'registrarPorQR'])->name('asistencia.registrar');
        Route::get('/carnets', function () {
            $estudiantes = Estudiante::with(['grado', 'grupo'])->get();
            return view('carnets', compact('estudiantes'));
        })->name('carnets');

        // Escritura de asistencia
        Route::post('/api/asistencia', [AsistenciaController::class, 'store']);
        Route::put('/api/asistencia/{id}', [AsistenciaController::class, 'update']);
        Route::delete('/api/asistencia/{id}', [AsistenciaController::class, 'destroy']);

        // Escritura de estudiantes
        Route::post('/api/estudiantes', [EstudianteController::class, 'store']);
        Route::put('/api/estudiantes/{id}', [EstudianteController::class, 'update']);
        Route::delete('/api/estudiantes/{id}', [EstudianteController::class, 'destroy']);

        // Escritura de grados, grupos y horarios
        Route::post('/grados', [GradoController::class, 'store']);
        Route::put('/grados/{grado}', [GradoController::class, 'update']);
        Route::delete('/grados/{grado}', [GradoController::class, 'destroy']);

        Route::post('/grupos', [GrupoController::class, 'store']);
        Route::put('/grupos/{grupo}', [GrupoController::class, 'update']);
        Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy']);

        Route::post('/horarios', [HorarioController::class, 'store']);
        Route::put('/horarios/{horario}', [HorarioController::class, 'update']);
        Route::delete('/horarios/{horario}', [HorarioController::class, 'destroy']);

        // Exportar el reporte de asistencia a PDF (mismos filtros que la
        // tabla en pantalla: buscar, grado, fecha).
        Route::get('/reporte/asistencia/pdf', [ReporteAsistenciaController::class, 'exportarAdminPdf'])
            ->name('reporte.asistencia.pdf');
    });
});
