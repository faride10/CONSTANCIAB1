<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Usuario; 
use App\Models\Docente;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Log;
use App\Imports\DocentesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class DocenteController extends Controller
{
    public function index()
    {
        return response()->json(Docente::with('grupo', 'usuario')->get());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'NOMBRE' => 'required|string|max:200',
            'RFC' => 'required|string|max:13|unique:docente,RFC',
            'CORREO' => 'nullable|email|max:200|unique:docente,CORREO',
            'TELEFONO' => 'nullable|string|max:30',
        ]);

        try {
            $docente = Docente::create($validatedData);

            $usuario = new Usuario();
            $usuario->USERNAME = $docente->RFC; 
            $usuario->PASSWORD_HASH = Hash::make($docente->RFC); 
            $usuario->ID_ROL = 2; 
            $usuario->needs_password_change = true; 
            $usuario->ID_DOCENTE = $docente->ID_DOCENTE; 
            $usuario->save();

            return response()->json($docente->load('usuario'), 201);

        } catch (\Exception $e) {
            Log::error("Error al crear docente y usuario: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al guardar el docente.'], 500);
        }
    }

    public function show(Docente $docente)
    {
        return response()->json($docente->load('grupo', 'usuario'));
    }

    public function update(Request $request, Docente $docente)
    {
        $validatedData = $request->validate([
            'NOMBRE' => 'sometimes|required|string|max:200',
            'RFC' => 'sometimes|required|string|max:13|unique:docente,RFC,' . $docente->ID_DOCENTE . ',ID_DOCENTE',
            'CORREO' => 'sometimes|nullable|email|max:200|unique:docente,CORREO,' . $docente->ID_DOCENTE . ',ID_DOCENTE',
            'TELEFONO' => 'sometimes|nullable|string|max:30',
        ]);

        $docente->update($validatedData);

        if ($request->has('RFC') && $docente->usuario) {
            $docente->usuario->update(['USERNAME' => $request->RFC]);
        }

        return response()->json($docente);
    }

    public function destroy(Docente $docente)
    {
        try {
            if ($docente->usuario) {
                $docente->usuario->delete();
            }
            $docente->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error("Error al eliminar docente: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al eliminar el docente.'], 500);
        }
    }
    
    public function importar(Request $request)
    {
    $request->validate([
        'archivo' => 'required|file|mimes:csv,xlsx'
    ]);

    try {
        $file = $request->file('archivo');

        Excel::import(new DocentesImport, $file);

        return response()->json(['message' => 'Importación completada con éxito.'], 200);

    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        return response()->json(['message' => 'La importación falló.', 'errors' => $e->failures()], 422);
    } catch (\Exception $e) {
        Log::error("Error al importar docentes: " . $e->getMessage());
        return response()->json(['message' => 'Error interno al importar el archivo.'], 500);
    }

    }

    public function getMiGrupo(Request $request)
    {
        $usuario = $request->user();
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no autenticado.'], 401);
        }
        
        $docente = $usuario->docente; 
        if (!$docente) {
            return response()->json(['message' => 'Perfil de docente no encontrado.'], 404);
        }
        
        $docente->load([
            'grupo' => function ($query) {
                $query->with('alumnos'); 
            }
        ]);

        if (!$docente->grupo) {
            return response()->json(['message' => 'No se encontró un grupo asignado a este docente.'], 404);
        }

        return response()->json([
            'grupo' => $docente->grupo
        ], 200);
    }
}