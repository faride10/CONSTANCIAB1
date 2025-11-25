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
        'NOMBRE_CONFERENCIA' => 'required|string|max:200',
        'TEMA' => 'nullable|string|max:255',
        'FECHA_HORA' => 'required|date',
        'LUGAR' => 'required|string|max:150',
        'NUM_PARTICIPANTES' => 'nullable|integer',
        'ID_PONENTE' => 'nullable|exists:ponente,ID_PONENTE',
        'grupos' => 'required|array',
        'grupos.*' => 'integer|exists:grupo,ID_GRUPO' 
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
        $conferencia = Conferencia::where('ID_CONFERENCIA', $id)->first();
        if (!$conferencia) {
            return response()->json(['message' => 'No encontrada'], 404);
        }
        return response()->json([
            'id' => $conferencia->ID_CONFERENCIA,
            'nombre' => $conferencia->NOMBRE_CONFERENCIA
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