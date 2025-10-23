<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlumnoController extends Controller
{
    public function index()
    {
        
        return response()->json(Alumno::with('grupo')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'NUM_CONTROL' => 'required|string|max:30|unique:ALUMNOS,NUM_CONTROL',
            'NOMBRE' => 'required|string|max:250',
            'CORREO_INSTITUCIONAL' => 'required|email|max:200|unique:ALUMNOS,CORREO_INSTITUCIONAL',
            'ID_GRUPO' => 'nullable|integer|exists:GRUPO,ID_GRUPO',
        ]);

        $alumno = Alumno::create($request->all());

        return response()->json($alumno, 201);
    }

    public function show(Alumno $alumno)
    {
    
        return response()->json($alumno->load('grupo', 'asistencias'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $request->validate([
            'NOMBRE' => 'sometimes|required|string|max:250',
            'CORREO_INSTITUCIONAL' => [
                'sometimes',
                'required',
                'email',
                'max:200',
        
                Rule::unique('ALUMNOS', 'CORREO_INSTITUCIONAL')->ignore($alumno->NUM_CONTROL, 'NUM_CONTROL'),
            ],
            'ID_GRUPO' => 'sometimes|nullable|integer|exists:GRUPO,ID_GRUPO',
        ]);

        $alumno->update($request->all());

        return response()->json($alumno);
    }

    public function destroy(Alumno $alumno)
    {
        $alumno->delete();

        return response()->json(null, 204);
    }
}