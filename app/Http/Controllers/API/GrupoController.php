<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class GrupoController extends Controller
{

    public function index()
    {
        return Grupo::with('docente')->get();
    }

    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:150',
            'carrera' => 'nullable|string|max:150',
            'id_docente' => 'nullable|exists:docente,id_docente' 
        ]);
        
        try {
            $grupo = Grupo::create($validatedData);
            return response()->json($grupo->load('docente'), 201);
        } catch (\Exception $e) {
            Log::error("Error al crear grupo: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al guardar el grupo.'], 500);
        }
    }

    public function show(Grupo $grupo)
    {
        return $grupo->load(['docente', 'alumnos']);
    }

    public function update(Request $request, Grupo $grupo)
    {
        $validatedData = $request->validate([
            'nombre' => 'sometimes|required|string|max:150',
            'carrera' => 'sometimes|nullable|string|max:150',
            'id_docente' => 'sometimes|nullable|exists:docente,id_docente'
        ]);
        
        try {
            $grupo->update($validatedData);
            return response()->json($grupo->load('docente'));
        } catch (\Exception $e) {
            Log::error("Error al actualizar grupo: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al actualizar el grupo.'], 500);
        }
    }

    public function destroy(Grupo $grupo)
    {
        try {
            $grupo->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
             Log::error("Error al eliminar grupo: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al eliminar el grupo.'], 500);
        }
    }

    public function getDocenteByGroupId($id)
{
    $grupo = \App\Models\Grupo::with('docente')->find($id);

    if (!$grupo) {
        return response()->json(['message' => 'Grupo no encontrado.'], 404);
    }
    
    $docenteNombre = $grupo->docente ? $grupo->docente->nombre : 'No Asignado';

    return response()->json([
        'docenteNombre' => $docenteNombre,
    ]);
}
}