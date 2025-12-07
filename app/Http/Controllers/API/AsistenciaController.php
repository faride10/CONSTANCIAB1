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
            $query->where('id_conferencia', $request->conferencia_id);
        }

        if ($request->has('grupo_id')) {
            $query->whereHas('alumno', function ($q) use ($request) {
                $q->where('id_grupo', $request->grupo_id);
            });
        }

        $query->orderBy('created_at', 'desc');

        return $query->get();
    }

    public function store(Request $request)
    {
     
        $validated = $request->validate([
            'id_conferencia' => 'required|exists:conferencia,id_conferencia',
            'id_grupo'       => 'required|exists:grupo,id_grupo',             
            'num_control'    => 'required|exists:alumno,num_control',         
        ]);

        $user = $request->user(); 

        if ($user->ID_ROL == 1) {

        } else if ($user->id_rol == 2 && $user->docente) {
            if ($user->docente->ID_GRUPO != $validated['id_grupo']) {
                return response()->json(['message' => 'No tienes permiso para registrar asistencia en este grupo.'], 403);
            }
        } else {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $existe = Asistencia::where('id_conferencia', $validated['id_conferencia'])
                            ->where('num_control', $validated['num_control'])
                            ->first();

        if ($existe) {
            return response()->json(['message' => 'El alumno ya está registrado.'], 400);
        }

        $datos = [
            'id_conferencia' => $validated['id_conferencia'],
            'num_control' => $validated['num_control'],
            'status' => 'confirmed',
            'fecha_registro' => now()
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
        $asistencia = Asistencia::find($id);

        if (!$asistencia) {
            return response()->json(['message' => 'La asistencia no existe.'], 404);
        }

        $user = auth()->user(); 

        if ($user->id_rol == 1) {
            
        } 
        else if ($user->id_rol == 2) {
            $docente = $user->docente;
            
            if (!$docente) {
                return response()->json(['message' => 'Error: Tu usuario no tiene perfil de docente asociado.'], 500);
            }

            $alumno = $asistencia->alumno;

            if (!$alumno) {

            } else if ($docente->id_grupo != $alumno->id_grupo) {
                return response()->json([
                    'message' => "No tienes permiso. Tu Grupo es: " . $docente->id_grupo . " y el Alumno es del Grupo: " . $alumno->id_grupo
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
        $query = Asistencia::where('id_conferencia', $id)
                           ->with(['conferencia', 'alumno']);
        
        if ($request->has('grupo_id')) {
            $query->whereHas('alumno', function ($q) use ($request) {
                $q->where('id_grupo', $request->grupo_id);
            });
        }

        return response()->json($query->get());
    }
}