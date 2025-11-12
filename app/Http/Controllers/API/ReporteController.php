<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conferencia;
use App\Models\Asistencia; 
use App\Models\Grupo; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class ReporteController extends Controller
{
    
    public function getReportePorConferencia(Conferencia $conferencia)
    {
        $conferencia->load('grupos.docente', 'grupos.alumnos');
        $grupos = $conferencia->grupos;

        $asistencias = Asistencia::where('ID_CONFERENCIA', $conferencia->ID_CONFERENCIA)
                                 ->pluck('NUM_CONTROL') 
                                 ->keyBy(fn ($item) => $item); 

        $reporteGrupos = $grupos->map(function ($grupo) use ($asistencias) {
            
            $totalAlumnos = $grupo->alumnos->count();
            $totalAsistencias = 0;

            foreach ($grupo->alumnos as $alumno) {
                if (isset($asistencias[$alumno->NUM_CONTROL])) {
                    $totalAsistencias++;
                }
            }

            return [
                'ID_GRUPO' => $grupo->ID_GRUPO,
                'NOMBRE_GRUPO' => $grupo->NOMBRE,
                'DOCENTE_NOMBRE' => $grupo->docente ? $grupo->docente->NOMBRE : 'Sin Asignar',
                'TOTAL_ALUMNOS' => $totalAlumnos,
                'TOTAL_ASISTENCIAS' => $totalAsistencias,
            ];
        });

        return response()->json([
            'conferencia' => [
                'ID_CONFERENCIA' => $conferencia->ID_CONFERENCIA,
                'TITULO' => $conferencia->NOMBRE_CONFERENCIA,
            ],
            'reporte_grupos' => $reporteGrupos,
        ]);
    }
    public function getReportePorAlumnos(Conferencia $conferencia, Grupo $grupo)
    {
        $alumnosDelGrupo = $grupo->alumnos;
        $asistencias = Asistencia::where('ID_CONFERENCIA', $conferencia->ID_CONFERENCIA)
                                 ->pluck('NUM_CONTROL') 
                                 ->keyBy(fn ($item) => $item);  

        $reporteAlumnos = $alumnosDelGrupo->map(function ($alumno) use ($asistencias) {
        $asistio = isset($asistencias[$alumno->NUM_CONTROL]);

            return [
                'NUM_CONTROL' => $alumno->NUM_CONTROL,
                'NOMBRE_ALUMNO' => $alumno->NOMBRE . ' ' . ($alumno->APELLIDOS ?? ''),
                'ASISTIO' => $asistio,  
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
    }
}