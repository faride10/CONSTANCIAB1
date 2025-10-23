<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AsistenciaController extends Controller
{

    public function registrar(Request $request)
    {
        $request->validate([
            'ID_CONFERENCIA' => 'required|exists:CONFERENCIA,ID_CONFERENCIA',
            'ID_GRUPO' => 'required|exists:GRUPO,ID_GRUPO',
            'NUM_CONTROL' => [
                'required',
                'exists:ALUMNOS,NUM_CONTROL',
              
                Rule::unique('ASISTENCIA')->where(function ($query) use ($request) {
                    return $query->where('ID_CONFERENCIA', $request->ID_CONFERENCIA)
                                 ->where('ID_GRUPO', $request->ID_GRUPO);
                }),
            ],
        ]);

        $asistencia = Asistencia::create([
            'ID_CONFERENCIA' => $request->ID_CONFERENCIA,
            'NUM_CONTROL' => $request->NUM_CONTROL,
            'ID_GRUPO' => $request->ID_GRUPO,
        ]);

        return response()->json([
            'message' => 'Asistencia registrada exitosamente.'
        ], 201);
    }

 

    public function index()
    {
        return Asistencia::with(['conferencia', 'alumno'])->get();
    }

    public function store(Request $request)
    {
        Gate::authorize('isAdmin'); 
        $request->validate([
            'ID_CONFERENCIA' => 'required|exists:CONFERENCIA,ID_CONFERENCIA',
            'ID_GRUPO' => 'required|exists:GRUPO,ID_GRUPO',
            'NUM_CONTROL' => 'required|exists:ALUMNOS,NUM_CONTROL',
        ]);

        $asistencia = Asistencia::create($request->all());
        return response()->json($asistencia, 201);
    }

    public function show(Asistencia $asistencia)
    {
        return $asistencia->load(['conferencia', 'alumno']);
    }

    public function destroy(Asistencia $asistencia)
    {
        Gate::authorize('isAdmin'); 
        $asistencia->delete();
        return response()->json(null, 204);
    }
}