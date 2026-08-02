<?php
namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    /* READ: listar todos los grupos con su grado */
    public function index() {
        $grupos = Grupo::with('grado')->get();
        return response()->json($grupos);
    }

    /* CREATE: crear un grupo dentro de un grado */
    public function store(Request $request) {
        $request->validate([
            'nombre'   => 'required|string|max:20',
            'grado_id' => 'required|exists:grados,id',
        ]);

        $grupo = Grupo::create($request->all());

        return response()->json($grupo->load('grado'), 201);
    }

    /* READ: ver un grupo específico */
    public function show($id) {
        $grupo = Grupo::with('grado')->findOrFail($id);
        return response()->json($grupo);
    }

    /* UPDATE: editar un grupo */
    public function update(Request $request, $id) {
        $grupo = Grupo::findOrFail($id);
        $grupo->update($request->all());
        return response()->json($grupo->load('grado'));
    }

    /* DELETE: eliminar un grupo */
    public function destroy($id) {
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();
        return response()->json(['mensaje' => 'Grupo eliminado correctamente']);
    }
}
