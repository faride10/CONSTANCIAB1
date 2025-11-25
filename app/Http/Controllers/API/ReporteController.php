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

            $asistencias = Asistencia::where('ID_CONFERENCIA', $conferencia->ID_CONFERENCIA)
                                     ->pluck('NUM_CONTROL')
                                     ->flip(); 

            $reporteGrupos = $grupos->map(function ($grupo) use ($asistencias) {
                
                $totalAlumnos = $grupo->alumnos->count();
                $totalAsistencias = 0;

                foreach ($grupo->alumnos as $alumno) {
                    if ($asistencias->has($alumno->NUM_CONTROL)) {
                        $totalAsistencias++;
                    }
                }

                $docenteNombre = 'Sin Asignar';
                if ($grupo->docente) {
                    $docenteNombre = $grupo->docente->NOMBRE . ' ' . ($grupo->docente->APELLIDOS ?? '');
                }

                return [
                    'ID_GRUPO' => $grupo->ID_GRUPO,
                    'NOMBRE_GRUPO' => $grupo->NOMBRE,
                    'DOCENTE_NOMBRE' => $docenteNombre,
                    'TOTAL_ALUMNOS' => $totalAlumnos,
                    'TOTAL_ASISTENCIAS' => $totalAsistencias,
                ];
            });

            return response()->json([
                'conferencia' => [
                    'ID_CONFERENCIA' => $conferencia->ID_CONFERENCIA,
                    'TITULO' => $conferencia->NOMBRE_CONFERENCIA, 
                    'FECHA_HORA' => $conferencia->FECHA_HORA,
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

            $asistencias = Asistencia::where('ID_CONFERENCIA', $idConferencia)
                                     ->where('ID_GRUPO', $idGrupo)
                                     ->pluck('NUM_CONTROL')
                                     ->flip();

            $reporteAlumnos = $grupo->alumnos->map(function ($alumno) use ($asistencias) {
                return [
                    'NUM_CONTROL' => $alumno->NUM_CONTROL,
                    'NOMBRE_ALUMNO' => $alumno->NOMBRE . ' ' . ($alumno->APELLIDOS ?? ''),
                    'ASISTIO' => $asistencias->has($alumno->NUM_CONTROL),  
                ];
            });

            return response()->json([
                'conferencia' => [
                    'TITULO' => $conferencia->NOMBRE_CONFERENCIA,
                ],
                'grupo' => [
                    'NOMBRE_GRUPO' => $grupo->NOMBRE,
                ],
                'reporte_alumnos' => $reporteAlumnos,
            ]);
        } catch (\Exception $e) {
            Log::error("Error en Reporte Alumnos: " . $e->getMessage());
            return response()->json(['message' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }
}