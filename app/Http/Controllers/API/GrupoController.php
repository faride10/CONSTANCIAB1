<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index()
    {
        return Grupo::with(['docente', 'alumnos'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'NOMBRE' => 'required|string|max:150',
            'CARRERA' => 'nullable|string|max:150',
            'ID_DOCENTE' => 'nullable|unique:GRUPO,ID_DOCENTE|exists:DOCENTE,ID_DOCENTE'
        ]);
        $grupo = Grupo::create($request->all());
        return response()->json($grupo, 201);
    }

    public function show(Grupo $grupo)
    {
        return $grupo->load(['docente', 'alumnos']);
    }

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'NOMBRE' => 'sometimes|required|string|max:150',
            'CARRERA' => 'sometimes|nullable|string|max:150',
            'ID_DOCENTE' => 'sometimes|nullable|exists:DOCENTE,ID_DOCENTE|unique:GRUPO,ID_DOCENTE,' . $grupo->ID_GRUPO . ',ID_GRUPO'
        ]);
        $grupo->update($request->all());
        return response()->json($grupo);
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();
        return response()->json(null, 204);
    }
}