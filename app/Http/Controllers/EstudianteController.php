<?php
namespace App\Http\Controllers;
use App\Models\Estudiante;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    // Verifica que el grupo indicado pertenezca de verdad al grado indicado.
    // Sin esto, se podía crear un estudiante con grado "6°" y grupo "11°1"
    // — dos datos que no tienen nada que ver entre sí.
    private function validarGrupoPerteneceAGrado($gradoId, $grupoId, $fail) {
        $grupo = Grupo::find($grupoId);
        if ($grupo && (int) $grupo->grado_id !== (int) $gradoId) {
            $fail('El grupo seleccionado no pertenece al grado seleccionado.');
        }
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
            'grupo_id' => [
                'required',
                'exists:grupos,id',
                fn($attribute, $value, $fail) => $this->validarGrupoPerteneceAGrado($request->grado_id, $value, $fail),
            ],
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
            'grupo_id' => [
                'sometimes',
                'exists:grupos,id',
                function ($attribute, $value, $fail) use ($request, $estudiante) {
                    // Si no mandan grado_id en esta edición, se compara
                    // contra el grado que el estudiante ya tenía.
                    $gradoId = $request->grado_id ?? $estudiante->grado_id;
                    $this->validarGrupoPerteneceAGrado($gradoId, $value, $fail);
                },
            ],
        ]);

        $estudiante->update($data);

        return response()->json($this->formatear($estudiante->load(['grado', 'grupo'])));
    }

    public function destroy($id) {
        Estudiante::findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }
}
