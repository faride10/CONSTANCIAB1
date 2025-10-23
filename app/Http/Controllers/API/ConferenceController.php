<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conferencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ConferenceController extends Controller
{
    public function index()
    {
        return response()->json(Conferencia::with('ponente')->get());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOMBRE_CONFERENCIA' => 'required|string|max:250',
            'TEMA' => 'nullable|string',
            'FECHA' => 'required|date',
            'HORA' => 'required|date_format:H:i',
            'LUGAR' => 'required|string|max:250',
            'NUM_PARTICIPANTES' => 'nullable|integer|min:0',
            'ID_PONENTE' => 'nullable|integer|exists:PONENTE,ID_PONENTE'
        ]);

        try {
            $fecha = Carbon::parse($validatedData['FECHA']);
            $hora = Carbon::createFromFormat('H:i', $validatedData['HORA']);
            $fechaHora = $fecha->setTime($hora->hour, $hora->minute, 0);

        } catch (\Exception $e) {
            Log::error('Error al parsear fecha/hora en ConferenceController@store: ' . $e->getMessage() . ' | Datos: ' . json_encode($request->only(['FECHA', 'HORA'])));
            return response()->json(['message' => 'Formato de fecha u hora inválido. Usa AAAA-MM-DD y HH:MM.'], 422);
        }

        try {
            $conferencia = Conferencia::create([
                'NOMBRE_CONFERENCIA' => $validatedData['NOMBRE_CONFERENCIA'],
                'TEMA' => $validatedData['TEMA'] ?? null,
                'FECHA_HORA' => $fechaHora,
                'LUGAR' => $validatedData['LUGAR'],
                'NUM_PARTICIPANTES' => $validatedData['NUM_PARTICIPANTES'] ?? null,
                'ID_PONENTE' => $validatedData['ID_PONENTE'] ?? null,
            ]);

            return response()->json($conferencia->load('ponente'), 201);

        } catch (\Exception $e) {
            Log::error('Error al crear conferencia: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno al guardar la conferencia.'], 500);
        }
    }

    public function show(Conferencia $conferencia)
    {
        return response()->json($conferencia->load('ponente'));
    }

    public function update(Request $request, Conferencia $conferencia)
    {
        $validatedData = $request->validate([
            'NOMBRE_CONFERENCIA' => 'sometimes|required|string|max:250',
            'TEMA' => 'sometimes|nullable|string',
            'FECHA_HORA' => 'sometimes|nullable|date', 
            'LUGAR' => 'sometimes|nullable|string|max:250',
            'NUM_PARTICIPANTES' => 'sometimes|nullable|integer',
            'ID_PONENTE' => 'sometimes|nullable|integer|exists:PONENTE,ID_PONENTE'
        ]);

        $conferencia->update($validatedData);

        return response()->json($conferencia->load('ponente'));
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
}