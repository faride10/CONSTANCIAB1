<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conferencia;
use App\Models\Asistencia; 
use App\Models\Grupo; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class ReporteController extends Controller
{
    public function getReportePorConferencia($id) 
    {
        try {
            $conferencia = Conferencia::with(['grupos.docente', 'grupos.alumnos'])->find($id);

            if (!$conferencia) {
                return response()->json(['message' => 'Conferencia no encontrada'], 404);
            }

            $grupos = $conferencia->grupos;

            $asistencias = Asistencia::where('id_conferencia', $conferencia->id_conferencia)
                                     ->pluck('num_control')
                                     ->flip(); 

            $reporteGrupos = $grupos->map(function ($grupo) use ($asistencias) {
                
                $totalAlumnos = $grupo->alumnos->count();
                $totalAsistencias = 0;

                foreach ($grupo->alumnos as $alumno) {
                    if ($asistencias->has($alumno->num_control)) {
                        $totalAsistencias++;
                    }
                }

                $docenteNombre = 'Sin Asignar';
                if ($grupo->docente) {
                    $docenteNombre = $grupo->docente->nombre . ' ' . ($grupo->docente->apellidos ?? '');
                }

                return [
                    'id_grupo' => $grupo->id_grupo,
                    'nombre_grupo' => $grupo->nombre,
                    'docente_nombre' => $docenteNombre,
                    'total_alumnos' => $totalAlumnos,
                    'total_asistencias' => $totalAsistencias,
                ];
            });

            return response()->json([
                'conferencia' => [
                    'id_conferencia' => $conferencia->id_conferencia,
                    'titulo' => $conferencia->nombre_conferencia, 
                    'fecha_hora' => $conferencia->fecha_hora,
                ],
                'reporte_grupos' => $reporteGrupos,
            ]);

        } catch (\Exception $e) {
            Log::error("Error en ReporteController: " . $e->getMessage());
            return response()->json(['message' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    public function getReportePorAlumnos($idConferencia, $idGrupo)
    {
        try {
            $conferencia = Conferencia::find($idConferencia);
            $grupo = Grupo::with('alumnos')->find($idGrupo);

            if (!$conferencia || !$grupo) {
                return response()->json(['message' => 'Datos no encontrados'], 404);
            }

            $asistencias = Asistencia::where('id_conferencia', $idConferencia)
                                     ->where('id_grupo', $idGrupo)
                                     ->pluck('num_control')
                                     ->flip();

            $reporteAlumnos = $grupo->alumnos->map(function ($alumno) use ($asistencias) {
                return [
                    'num_control' => $alumno->num_control,
                    'nombre_alumno' => $alumno->nombre . ' ' . ($alumno->apellidos ?? ''),
                    'asistio' => $asistencias->has($alumno->num_control),  
                ];
            });

            return response()->json([
                'conferencia' => [
                    'titulo' => $conferencia->nombre_conferencia,
                ],
                'grupo' => [
                    'nombre_grupo' => $grupo->nombre,
                ],
                'reporte_alumnos' => $reporteAlumnos,
            ]);
        } catch (\Exception $e) {
            Log::error("Error en Reporte Alumnos: " . $e->getMessage());
            return response()->json(['message' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }
}