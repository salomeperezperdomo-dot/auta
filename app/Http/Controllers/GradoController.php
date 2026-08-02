<?php
namespace App\Http\Controllers;

use App\Models\Grado;
use Illuminate\Http\Request;

class GradoController extends Controller
{
    /* READ: listar todos los grados */
    public function index() {
        $grados = Grado::all();
        return response()->json($grados);
    }

    /* CREATE: crear un nuevo grado */
    public function store(Request $request) {
        $request->validate([
            'nombre'      => 'required|string|max:20',
            'descripcion' => 'nullable|string|max:100',
        ]);

        $grado = Grado::create($request->all());

        return response()->json($grado, 201);
    }

    /* READ: ver un grado específico */
    public function show($id) {
        $grado = Grado::findOrFail($id);
        return response()->json($grado);
    }

    /* UPDATE: editar un grado */
    public function update(Request $request, $id) {
        $grado = Grado::findOrFail($id);
        $grado->update($request->all());
        return response()->json($grado);
    }

    /* DELETE: eliminar un grado */
    public function destroy($id) {
        $grado = Grado::findOrFail($id);
        $grado->delete();
        return response()->json(['mensaje' => 'Grado eliminado correctamente']);
    }
}
