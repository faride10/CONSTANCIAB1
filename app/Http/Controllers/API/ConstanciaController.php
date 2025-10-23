<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Constancia;
use Illuminate\Http\Request;
use App\Models\Alumno;

class ConstanciaController extends Controller
{
    public function index()
    {
        return Constancia::with('alumno')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'NUM_CONTROL' => 'required|unique:CONSTANCIA,NUM_CONTROL|exists:ALUMNOS,NUM_CONTROL',
            'FECHA_EMISION' => 'nullable|date',
        ]);
        $constancia = Constancia::create($request->all());
        return response()->json($constancia, 201);
    }

    public function show(Constancia $constancia)
    {
        return $constancia->load('alumno');
    }

    public function update(Request $request, Constancia $constancia)
    {
        $request->validate([
            'FECHA_EMISION' => 'sometimes|nullable|date',
        ]);
        $constancia->update($request->all());
        return response()->json($constancia);
    }

    public function destroy(Constancia $constancia)
    {
        $constancia->delete();
        return response()->json(null, 204);
    }


public function generar(Alumno $alumno)
{
    $minimo_asistencias = 3;

    $constanciaExistente = Constancia::where('NUM_CONTROL', $alumno->NUM_CONTROL)->exists();
    if ($constanciaExistente) {
        return response()->json(['message' => 'Este alumno ya tiene una constancia generada.'], 409);
    }

    $numeroDeAsistencias = $alumno->asistencias()->count();

    if ($numeroDeAsistencias >= $minimo_asistencias) {
        $constancia = Constancia::create([
            'NUM_CONTROL' => $alumno->NUM_CONTROL
        ]);

        return response()->json([
            'message' => 'Constancia generada exitosamente.',
            'constancia' => $constancia,
            'asistencias_contadas' => $numeroDeAsistencias,
        ], 201);
    } else {

        return response()->json([
            'message' => 'El alumno no cumple con el mínimo de asistencias requeridas.',
            'asistencias_contadas' => $numeroDeAsistencias,
            'asistencias_requeridas' => $minimo_asistencias
        ], 422);
    }
}
}