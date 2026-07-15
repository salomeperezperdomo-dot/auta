<?php
namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /* READ: listar todos los logs con el usuario que los generó */
    public function index() {
        $logs = Log::with('usuario')
                   ->orderBy('fecha_hora', 'desc')
                   ->get();
        return response()->json($logs);
    }

    /* CREATE: registrar una acción (se llama internamente desde otros controladores) */
    public function store(Request $request) {
        $request->validate([
            'usuario_id'      => 'required|exists:usuarios,id',
            'accion'          => 'required|in:crear,editar,eliminar,login,logout',
            'tabla_afectada'  => 'required|string',
        ]);

        $log = Log::create($request->all());
        return response()->json($log, 201);
    }

    /* READ: ver log específico */
    public function show($id) {
        $log = Log::with('usuario')->findOrFail($id);
        return response()->json($log);
    }

    /* No hay update ni delete en logs — el historial no se modifica */
}