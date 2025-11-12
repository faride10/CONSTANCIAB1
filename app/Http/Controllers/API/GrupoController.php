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
            'NOMBRE' => 'required|string|max:150',
            'CARRERA' => 'nullable|string|max:150',
            'ID_DOCENTE' => 'nullable|exists:docente,ID_DOCENTE' 
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
            'NOMBRE' => 'sometimes|required|string|max:150',
            'CARRERA' => 'sometimes|nullable|string|max:150',
            'ID_DOCENTE' => 'sometimes|nullable|exists:docente,ID_DOCENTE'
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
}