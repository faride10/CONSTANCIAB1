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
            'num_control' => 'required|unique:constancia,num_control|exists:alumnos,num_control',
            'fecha_emision' => 'nullable|date',
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
            'fecha_emision' => 'sometimes|nullable|date',
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

    $constanciaExistente = Constancia::where('num_control', $alumno->num_control)->exists();
    if ($constanciaExistente) {
        return response()->json(['message' => 'Este alumno ya tiene una constancia generada.'], 409);
    }

    $numeroDeAsistencias = $alumno->asistencias()->count();

    if ($numeroDeAsistencias >= $minimo_asistencias) {
        $constancia = Constancia::create([
            'num_control' => $alumno->num_control
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