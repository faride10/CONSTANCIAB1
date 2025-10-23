<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function index()
    {
        return Docente::with('grupo')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'NOMBRE' => 'required|string|max:200',
            'CORREO' => 'nullable|email|max:200|unique:DOCENTE,CORREO',
            'TELEFONO' => 'nullable|string|max:30',
        ]);
        $docente = Docente::create($request->all());
        return response()->json($docente, 201);
    }

    public function show(Docente $docente)
    {
        return $docente->load('grupo');
    }

    public function update(Request $request, Docente $docente)
    {
        $request->validate([
            'NOMBRE' => 'sometimes|required|string|max:200',
            'CORREO' => 'sometimes|nullable|email|max:200|unique:DOCENTE,CORREO,' . $docente->ID_DOCENTE . ',ID_DOCENTE',
            'TELEFONO' => 'sometimes|nullable|string|max:30',
        ]);
        $docente->update($request->all());
        return response()->json($docente);
    }

    public function destroy(Docente $docente)
    {
        $docente->delete();
        return response()->json(null, 204);
    }
}