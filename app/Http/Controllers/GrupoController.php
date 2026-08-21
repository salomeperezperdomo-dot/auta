<?php
namespace App\Http\Controllers;

use App\Models\Grado;
use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    /* READ: listar todos los grupos con su grado */
    public function index() {
        $grupos = Grupo::with('grado')->orderBy('grado_id')->orderBy('numero')->get();
        return response()->json($grupos);
    }

    /* CREATE: crear un grupo a partir de grado_id + numero (nunca texto libre) */
    public function store(Request $request) {
        $data = $request->validate([
            'grado_id' => 'required|exists:grados,id',
            'numero'   => 'required|integer|min:1|max:99',
        ]);

        $existe = Grupo::where('grado_id', $data['grado_id'])
                        ->where('numero', $data['numero'])
                        ->exists();
        if ($existe) {
            return response()->json(['message' => 'Ese grupo ya existe para el grado seleccionado.'], 422);
        }

        $grado = Grado::findOrFail($data['grado_id']);

        $grupo = Grupo::create([
            'grado_id' => $grado->id,
            'numero'   => $data['numero'],
            'nombre'   => $grado->nombre . $data['numero'], // se arma solo, ej: "6°" + 1 = "6°1"
        ]);

        return response()->json($grupo->load('grado'), 201);
    }

    /* READ: ver un grupo específico */
    public function show($id) {
        $grupo = Grupo::with('grado')->findOrFail($id);
        return response()->json($grupo);
    }

    /* UPDATE: editar grado y/o número de un grupo (el nombre se recalcula solo) */
    public function update(Request $request, $id) {
        $grupo = Grupo::findOrFail($id);

        $data = $request->validate([
            'grado_id' => 'sometimes|exists:grados,id',
            'numero'   => 'sometimes|integer|min:1|max:99',
        ]);

        $gradoId = $data['grado_id'] ?? $grupo->grado_id;
        $numero  = $data['numero']   ?? $grupo->numero;

        $existe = Grupo::where('grado_id', $gradoId)
                        ->where('numero', $numero)
                        ->where('id', '!=', $grupo->id)
                        ->exists();
        if ($existe) {
            return response()->json(['message' => 'Ese grupo ya existe para el grado seleccionado.'], 422);
        }

        $grado = Grado::findOrFail($gradoId);

        $grupo->update([
            'grado_id' => $gradoId,
            'numero'   => $numero,
            'nombre'   => $grado->nombre . $numero,
        ]);

        return response()->json($grupo->load('grado'));
    }

    /* DELETE: eliminar un grupo */
    public function destroy($id) {
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();
        return response()->json(['mensaje' => 'Grupo eliminado correctamente']);
    }
}
