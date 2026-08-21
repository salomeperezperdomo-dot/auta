<?php
namespace App\Http\Controllers;

use App\Models\Grado;
use Illuminate\Http\Request;

class GradoController extends Controller
{
    /* READ: listar todos los grados */
    public function index() {
        // num_estudiantes se calcula en vivo (conteo real de la relación),
        // no se confía en la columna guardada — esa nunca se actualizaba
        // cuando se creaba, movía o eliminaba un estudiante, así que
        // mostraba un número desactualizado.
        $grados = Grado::withCount('estudiantes')->get();
        $grados->each(fn($g) => $g->num_estudiantes = $g->estudiantes_count);
        return response()->json($grados);
    }

    /* CREATE: crear un nuevo grado */
    public function store(Request $request) {
        $request->validate([
            'nombre'      => 'required|string|max:20',
            'descripcion' => 'nullable|string|max:100',
        ]);

        $grado = Grado::create($request->only(['nombre', 'descripcion']));

        return response()->json($grado, 201);
    }

    /* READ: ver un grado específico */
    public function show($id) {
        $grado = Grado::withCount('estudiantes')->findOrFail($id);
        $grado->num_estudiantes = $grado->estudiantes_count;
        return response()->json($grado);
    }

    /* UPDATE: editar un grado */
    public function update(Request $request, $id) {
        $grado = Grado::findOrFail($id);
        $grado->update($request->only(['nombre', 'descripcion']));
        return response()->json($grado);
    }

    /* DELETE: eliminar un grado */
    public function destroy($id) {
        $grado = Grado::findOrFail($id);
        $grado->delete();
        return response()->json(['mensaje' => 'Grado eliminado correctamente']);
    }
}
