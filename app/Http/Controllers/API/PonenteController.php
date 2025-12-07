<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Ponente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;     

class PonenteController extends Controller
{
    public function index()
    {
        return response()->json(Ponente::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:200',
            'titulo' => 'nullable|string|max:150',
            'cargo' => 'nullable|string|max:150',
            'empresa' => 'nullable|string|max:200',
            'correo' => 'nullable|email|max:200'
        ]);

        try {
            $ponente = Ponente::create($validatedData);
            
            ActivityLog::create([
                'tipo_accion' => 'docente',     
                'descripcion' => 'El ponente "' . $ponente->nombre . '" ha sido registrado.'
            ]);
           
            return response()->json($ponente, 201); 

        } catch (\Exception $e) {
            Log::error("Error al crear ponente o su log: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al guardar el ponente.'], 500);
        }

    }

    public function show(Ponente $ponente)
    {
        return response()->json($ponente->load('conferencias'));
    }

    public function update(Request $request, Ponente $ponente)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|max:2200',
            'titulo' => 'sometimes|nullable|string|max:150',
            'cargo' => 'sometimes|nullable|string|max:150',
            'empresa' => 'sometimes|nullable|string|max:200',
            'correo' => 'sometimes|nullable|email|max:200',
        ]);

        $ponente->update($request->all());

        return response()->json($ponente);
    }

    public function destroy(Ponente $ponente) 
    {
        try {
            $ponente->delete(); 
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error("Error al eliminar ponente ID {$ponente->id_ponente}: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al eliminar el ponente.'], 500);
        }
    }
}