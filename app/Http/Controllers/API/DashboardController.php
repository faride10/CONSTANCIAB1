<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conferencia;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Constancia;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    
    public function adminSummary(Request $request)
    {
        
        $activeConferences = Conferencia::count();
        $registeredStudents = Alumno::count();
        $activeTeachers = Docente::count();
        $issuedCertificates = Constancia::count();

        return response()->json([
            'active_conferences' => $activeConferences,
            'registered_students' => $registeredStudents,
            'active_teachers' => $activeTeachers,
            'issued_certificates' => $issuedCertificates,
        ]);
    }
    public function getRecentActivities()
{
    $actividades = ActivityLog::orderBy('created_at', 'desc') 
    ->limit(5) 
    ->get();

    return response()->json($actividades);
}
public function getMiGrupo(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no autenticado.'], 401);
        }
        
        $docente = $usuario->docente; 

        if (!$docente) {
            return response()->json(['message' => 'No se encontró el perfil de docente asociado.'], 404);
        }
        
        $grupo = $docente->load([
            'grupo' => function ($query) {
                $query->with('alumnos'); 
            }
        ]);

        if (!$grupo) {
            return response()->json(['message' => 'No se encontró un grupo asignado.'], 404);
        }

        return response()->json([
            'grupo' => $grupo->grupo,   
            'docente_id' => $docente->id_docente
        ], 200);
    }
}