<?php
namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    /* READ: listar todos los horarios con su grado */
    public function index() {
        $horarios = Horario::with('grado')->get();
        return response()->json($horarios);
    }

    /* CREATE: crear horario para un grado */
    public function store(Request $request) {
        $request->validate([
            'grado_id'     => 'required|exists:grados,id|unique:horarios,grado_id',
            'hora_entrada' => 'required',
            'hora_limite'  => 'required',
        ], [
            'grado_id.unique' => 'Ese grado ya tiene un horario configurado. Edítalo en vez de crear uno nuevo.',
        ]);

        $horario = Horario::create($request->all());

        return response()->json($horario, 201);
    }

    /* READ: ver horario de un grado específico */
    public function show($id) {
        $horario = Horario::with('grado')->findOrFail($id);
        return response()->json($horario);
    }

    /* UPDATE: actualizar horario */
    public function update(Request $request, $id) {
        $horario = Horario::findOrFail($id);

        $request->validate([
            'grado_id'     => 'sometimes|exists:grados,id|unique:horarios,grado_id,' . $id,
            'hora_entrada' => 'sometimes|required',
            'hora_limite'  => 'sometimes|required',
        ], [
            'grado_id.unique' => 'Ese grado ya tiene un horario configurado.',
        ]);

        $horario->update($request->all());
        return response()->json($horario);
    }

    /* DELETE: eliminar horario */
    public function destroy($id) {
        $horario = Horario::findOrFail($id);
        $horario->delete();
        return response()->json(['mensaje' => 'Horario eliminado correctamente']);
    }
}
