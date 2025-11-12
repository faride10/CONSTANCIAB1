<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Imports\AlumnosImport;
use Maatwebsite\Excel\Facades\Excel;

class AlumnoController extends Controller
{
    public function index()
    {
        return Alumno::with('grupo')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NUM_CONTROL' => 'required|string|max:30|unique:alumno,NUM_CONTROL',
            'NOMBRE' => 'required|string|max:250',
            'CORREO_INSTITUCIONAL' => 'required|string|email|max:200|unique:alumno,CORREO_INSTITUCIONAL',
            'ID_GRUPO' => 'nullable|exists:grupo,ID_GRUPO'
        ]);

        try {
            $alumno = Alumno::create($validatedData);
            return response()->json($alumno->load('grupo'), 201);
        } catch (\Exception $e) {
            Log::error("Error al crear alumno: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al guardar el alumno.'], 500);
        }
    }

    public function show($numControl)
    {
        $alumno = Alumno::find($numControl);
        if (!$alumno) {
            return response()->json(['message' => 'Alumno no encontrado'], 404);
        }
        return $alumno->load('grupo');
    }

    public function update(Request $request, $numControl)
    {
        $alumno = Alumno::find($numControl);
        if (!$alumno) {
            return response()->json(['message' => 'Alumno no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'NUM_CONTROL' => 'sometimes|required|string|max:30|unique:alumno,NUM_CONTROL,' . $numControl . ',NUM_CONTROL',
            'NOMBRE' => 'sometimes|required|string|max:250',
            'CORREO_INSTITUCIONAL' => 'sometimes|required|string|email|max:200|unique:alumno,CORREO_INSTITUCIONAL,' . $numControl . ',NUM_CONTROL',
            'ID_GRUPO' => 'nullable|exists:grupo,ID_GRUPO'
        ]);

        try {
            $alumno->update($validatedData);
            return response()->json($alumno->load('grupo'));
        } catch (\Exception $e) {
            Log::error("Error al actualizar alumno: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al actualizar el alumno.'], 500);
        }
    }

    public function destroy($numControl)
    {
        $alumno = Alumno::find($numControl);
        if (!$alumno) {
            return response()->json(['message' => 'Alumno no encontrado'], 404);
        }

        try {
            $alumno->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error("Error al eliminar alumno: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al eliminar el alumno.'], 500);
        }
    }
    
    public function importar(Request $request)
{
    $request->validate([
        'archivo' => 'required|file|mimes:csv,xlsx'
    ]);

    try {
                $file = $request->file('archivo');

        Excel::import(new AlumnosImport, $file);

        return response()->json(['message' => 'Importación completada con éxito.'], 200);

    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        return response()->json(['message' => 'La importación falló.', 'errors' => $e->failures()], 422);
    } catch (\Exception $e) {
        Log::error("Error al importar alumnos: " . $e->getMessage());
        return response()->json(['message' => 'Error interno al importar el archivo.'], 500);
    }
}
}
