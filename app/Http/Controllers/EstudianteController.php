<?php
namespace App\Http\Controllers;
use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller {

    public function index() {
        $estudiantes = Estudiante::all();
        return response()->json($estudiantes);
    }

    public function store(Request $request) {
        $estudiante = Estudiante::create([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'grado'  => $request->grado,
        ]);
        return response()->json($estudiante);
    }

    public function update(Request $request, $id) {
        $estudiante = Estudiante::findOrFail($id);
        $estudiante->update([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'grado'  => $request->grado,
        ]);
        return response()->json($estudiante);
    }

    public function destroy($id) {
        Estudiante::findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }
}