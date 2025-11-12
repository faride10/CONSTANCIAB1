<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conferencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Grupo;

class ConferenceController extends Controller
{
    public function index()
    {
        return Conferencia::with('ponente', 'grupos')->get();
    }
    
    public function store(Request $request)
    {
    
        $validatedData = $request->validate([
            'NOMBRE_CONFERENCIA' => 'required|string|max:250',
            'TEMA' => 'nullable|string',
            'FECHA_HORA' => 'required|date',    
            'LUGAR' => 'required|string|max:250',
            'NUM_PARTICIPANTES' => 'nullable|integer|min:0',
            'ID_PONENTE' => 'nullable|integer|exists:ponente,ID_PONENTE', 
            'grupos' => 'nullable|array'    
        ]);

        try {
            $conferencia = Conferencia::create([
                'NOMBRE_CONFERENCIA' => $validatedData['NOMBRE_CONFERENCIA'],
                'TEMA' => $validatedData['TEMA'] ?? null,
                'FECHA_HORA' => $validatedData['FECHA_HORA'], 
                'LUGAR' => $validatedData['LUGAR'],
                'NUM_PARTICIPANTES' => $validatedData['NUM_PARTICIPANTES'] ?? null,
                'ID_PONENTE' => $validatedData['ID_PONENTE'] ?? null,
            ]);

            $conferencia->grupos()->sync($request->input('grupos', []));
            
            return response()->json($conferencia->load('ponente', 'grupos'), 201);

        } catch (\Exception $e) {
            Log::error('Error al crear conferencia: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno al guardar la conferencia.'], 500);
        }
    }

    public function show(Conferencia $conferencia)
    {
        return response()->json($conferencia->load('ponente', 'grupos'));
    }

    public function update(Request $request, Conferencia $conferencia)
    {
        $validatedData = $request->validate([
            'NOMBRE_CONFERENCIA' => 'sometimes|required|string|max:250',
            'TEMA' => 'sometimes|nullable|string',
            'FECHA_HORA' => 'sometimes|nullable|date', 
            'LUGAR' => 'sometimes|nullable|string|max:250',
            'NUM_PARTICIPANTES' => 'sometimes|nullable|integer',
            'ID_PONENTE' => 'sometimes|nullable|integer|exists:ponente,ID_PONENTE',    
            'grupos' => 'nullable|array'    
        ]);

        $conferencia->update($validatedData);

        if ($request->has('grupos')) {
            $conferencia->grupos()->sync($request->input('grupos', []));
        }

        return response()->json($conferencia->load('ponente', 'grupos'));
    }

    public function destroy(Conferencia $conferencia)
    {
        try {
            $conferencia->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar conferencia ID ' . $conferencia->ID_CONFERENCIA . ': ' . $e->getMessage());
            return response()->json(['message' => 'Error interno al eliminar la conferencia.'], 500);
        }
    }

    public function generarQrCode($idConferencia, $idGrupo)
    {
        $conferencia = Conferencia::find($idConferencia);
        if (!$conferencia || !$conferencia->grupos()->where('grupo.ID_GRUPO', $idGrupo)->exists()) {
            return response()->json(['message' => 'Grupo no asignado a esta conferencia.'], 404);
        }

        $urlDeAsistencia = 'http://localhost:4200/asistencia/' . $idConferencia . '/' . $idGrupo;

        $qrCode = QrCode::format('svg')
                        ->size(250)
                        ->generate($urlDeAsistencia);

        return Response::make($qrCode, 200, [
            'Content-Type' => 'image/svg+xml'
        ]);
    }
}