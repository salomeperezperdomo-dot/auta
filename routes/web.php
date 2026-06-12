<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\UsuarioController;
use App\Models\Estudiante;

// Vista del escáner QR (solo para docentes y admin)
Route::get('/escaner', [AsistenciaController::class, 'mostrarEscaner'])->name('escaner');

// Registrar asistencia desde el QR
Route::post('/registrar-asistencia', [AsistenciaController::class, 'registrarPorQR'])->name('asistencia.registrar');

// Vistas
Route::get('/', function () { return view('index'); });
Route::get('/login', function () { return view('login'); });
Route::get('/panel', function () { return view('panel'); });

// API Estudiantes
Route::get('/api/estudiantes', [EstudianteController::class, 'index']);
Route::post('/api/estudiantes', [EstudianteController::class, 'store']);
Route::put('/api/estudiantes/{id}', [EstudianteController::class, 'update']);
Route::delete('/api/estudiantes/{id}', [EstudianteController::class, 'destroy']);

// API Asistencia
Route::get('/api/asistencia', [AsistenciaController::class, 'index']);
Route::post('/api/asistencia', [AsistenciaController::class, 'store']);
Route::delete('/api/asistencia/{id}', [AsistenciaController::class, 'destroy']);

// API Login
Route::post('/api/login', [UsuarioController::class, 'login']);

Route::get('/carnets', function () {
    $estudiantes = Estudiante::all();
    return view('carnets', compact('estudiantes'));
})->name('carnets');

Route::put('/api/asistencia/{id}', [AsistenciaController::class, 'update']);