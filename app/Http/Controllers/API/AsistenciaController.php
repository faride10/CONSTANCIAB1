<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate; 

class AsistenciaController extends Controller
{
    public function registrar(Request $request)
    {
    }

    public function index(Request $request)
    {
        $query = Asistencia::with(['conferencia', 'alumno']);

        if ($request->has('conferencia_id')) {
            $query->where('ID_CONFERENCIA', $request->conferencia_id);
        }

        if ($request->has('grupo_id')) {
            $query->whereHas('alumno', function ($q) use ($request) {
                $q->where('ID_GRUPO', $request->grupo_id);
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query->get();
    }

    public function store(Request $request)
    {
     
        $validated = $request->validate([
            'ID_CONFERENCIA' => 'required|exists:conferencia,ID_CONFERENCIA',
            'ID_GRUPO'       => 'required|exists:grupo,ID_GRUPO',             
            'NUM_CONTROL'    => 'required|exists:alumno,NUM_CONTROL',         
        ]);

        $user = $request->user(); 

        if ($user->ID_ROL == 1) {
            // Admin
        } else if ($user->ID_ROL == 2 && $user->docente) {
            if ($user->docente->ID_GRUPO != $validated['ID_GRUPO']) {
                return response()->json(['message' => 'No tienes permiso para registrar asistencia en este grupo.'], 403);
            }
        } else {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        // Verificar si ya existe para evitar duplicados
        $existe = Asistencia::where('ID_CONFERENCIA', $validated['ID_CONFERENCIA'])
                            ->where('NUM_CONTROL', $validated['NUM_CONTROL'])
                            ->first();

        if ($existe) {
            return response()->json(['message' => 'El alumno ya está registrado.'], 400);
        }

        $datos = [
            'ID_CONFERENCIA' => $validated['ID_CONFERENCIA'],
            'NUM_CONTROL' => $validated['NUM_CONTROL'],
            'STATUS' => 'confirmed',
            'FECHA_REGISTRO' => now()
        ];

        $asistencia = Asistencia::create($datos);
        return response()->json($asistencia, 201);
    }

    public function show(Asistencia $asistencia)
    {
        return $asistencia->load(['conferencia', 'alumno']);
    }

    public function destroy($id)
    {
        // Buscamos manual para asegurar que existe
        $asistencia = Asistencia::find($id);

        if (!$asistencia) {
            return response()->json(['message' => 'La asistencia no existe.'], 404);
        }

        $user = auth()->user(); 

        if ($user->ID_ROL == 1) {
            // Admin
        } 
        else if ($user->ID_ROL == 2) {
            $docente = $user->docente;
            
            if (!$docente) {
                return response()->json(['message' => 'Error: Tu usuario no tiene perfil de docente asociado.'], 500);
            }

            $alumno = $asistencia->alumno;

            if (!$alumno) {
                // Permitir borrar si el alumno ya no existe
            } else if ($docente->ID_GRUPO != $alumno->ID_GRUPO) {
                return response()->json([
                    'message' => "No tienes permiso. Tu Grupo es: " . $docente->ID_GRUPO . " y el Alumno es del Grupo: " . $alumno->ID_GRUPO
                ], 403);
            }
        } 
        else {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $asistencia->delete();
        return response()->json(['message' => 'Asistencia eliminada correctamente.'], 200);
    } 

    public function porConferencia(Request $request, $id)
    {
        $query = Asistencia::where('ID_CONFERENCIA', $id)
                           ->with(['conferencia', 'alumno']);
        
        if ($request->has('grupo_id')) {
            $query->whereHas('alumno', function ($q) use ($request) {
                $q->where('ID_GRUPO', $request->grupo_id);
            });
        }

        return response()->json($query->get());
    }
}