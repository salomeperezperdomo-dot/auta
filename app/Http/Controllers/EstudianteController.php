<?php
namespace App\Http\Controllers;
use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller {

    // Aplana el estudiante para que el JSON siga trayendo "grado" y "grupo"
    // como texto (compatibilidad con el resto del panel), además de los
    // IDs reales por si se necesitan más adelante.
    private function formatear(Estudiante $estudiante) {
        return [
            'id'        => $estudiante->id,
            'nombre'    => $estudiante->nombre,
            'codigo'    => $estudiante->codigo,
            'grado_id'  => $estudiante->grado_id,
            'grado'     => $estudiante->grado?->nombre,
            'grupo_id'  => $estudiante->grupo_id,
            'grupo'     => $estudiante->grupo?->nombre,
        ];
    }

    public function index() {
        $estudiantes = Estudiante::with(['grado', 'grupo'])->get();
        return response()->json($estudiantes->map(fn($e) => $this->formatear($e)));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'nombre'   => 'required|string|max:100',
            'codigo'   => 'required|string|max:50|unique:estudiantes,codigo',
            'grado_id' => 'required|exists:grados,id',
            'grupo_id' => 'required|exists:grupos,id',
        ]);

        $estudiante = Estudiante::create($data);

        return response()->json($this->formatear($estudiante->load(['grado', 'grupo'])), 201);
    }

    public function update(Request $request, $id) {
        $estudiante = Estudiante::findOrFail($id);

        $data = $request->validate([
            'nombre'   => 'sometimes|string|max:100',
            'codigo'   => 'sometimes|string|max:50|unique:estudiantes,codigo,' . $id,
            'grado_id' => 'sometimes|exists:grados,id',
            'grupo_id' => 'sometimes|exists:grupos,id',
        ]);

        $estudiante->update($data);

        return response()->json($this->formatear($estudiante->load(['grado', 'grupo'])));
    }

    public function destroy($id) {
        Estudiante::findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }
}
