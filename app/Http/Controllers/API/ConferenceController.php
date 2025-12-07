<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Conferencia;
use App\Models\Grupo; 
use Carbon\Carbon;

class ConferenceController extends Controller
{
    public function index()
    {
        return Conferencia::with('ponente', 'grupos')->get();
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'nombre_conferencia' => 'required|string|max:200',
        'tema' => 'nullable|string|max:255',
        'fecha_hora' => 'required|date',
        'lugar' => 'required|string|max:150',
        'num_participantes' => 'nullable|integer',
        'id_ponente' => 'nullable|exists:ponente,id_ponente',
        'grupos' => 'required|array',
        'grupos.*' => 'integer|exists:grupo,id_grupo', 
    ]);

    try {
        $conferencia = Conferencia::create($validatedData);

        if (!empty($validatedData['grupos'])) {
            $conferencia->grupos()->attach($validatedData['grupos']);
        }

        return response()->json($conferencia->load('grupos'), 201);

    } catch (\Exception $e) {
        \Log::error("Error al crear conferencia y asignar grupos: " . $e->getMessage());
        return response()->json(['message' => 'Error interno al guardar la conferencia.', 'details' => $e->getMessage()], 500);
    }
}
    
    public function getPublicInfo($id)
    {
        $conferencia = Conferencia::where('id_conferencia', $id)->first();
        if (!$conferencia) {
            return response()->json(['message' => 'No encontrada'], 404);
        }
        return response()->json([
            'id' => $conferencia->id_conferencia,
            'nombre' => $conferencia->nombre_conferencia
        ]);
    } 

    public function destroy(Conferencia $conferencia)
    {
        try {
       
            $conferencia->grupos()->detach();
            $conferencia->asistencias()->delete(); 
            $conferencia->delete();

            return response()->json(null, 204);

        } catch (\Exception $e) {
            \Log::error("Error eliminando conferencia: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al eliminar. Es posible que falten relaciones en el Modelo.', 'details' => $e->getMessage()], 500);
        }
    }
}