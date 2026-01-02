<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Alumno;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function requestAttendance(Request $request)
    {
        $request->validate([
            'control_number' => 'required|string',
            'conference_id' => 'required|exists:conferencia,id_conferencia',
        ]);

        $alumno = Alumno::where('num_control', $request->control_number)->first();

        if (!$alumno) {
            return response()->json(['message' => 'Número de control no encontrado.'], 404);
        }

        $conferencia = DB::table('conferencia')
            ->where('id_conferencia', $request->conference_id)
            ->first();

        $existingAttendance = Asistencia::where('id_conferencia', $request->conference_id)
                                ->where('num_control', $request->control_number)
                                ->first();

        if ($existingAttendance && $existingAttendance->status === 'confirmed') {
            return response()->json(['message' => 'Ya has registrado tu asistencia previamente. ✅'], 400);
        }

        Asistencia::updateOrCreate(
            [
                'id_conferencia' => $request->conference_id,
                'num_control' => $request->control_number
            ],
            [
                'status' => 'confirmed',     
                'fecha_registro' => now()
            ]
        );

        if ($conferencia && isset($conferencia->id_docente)) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\AsistenciaAlumno',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $conferencia->id_docente, 
                'data' => json_encode([
                    'titulo' => 'Nueva Asistencia',
                    'mensaje' => "El alumno {$alumno->nombre} registró asistencia en: {$conferencia->nombre_conferencia}",
                    'tipo' => 'SUCCESS'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => '¡Tu asistencia ha sido registrada correctamente! ✅'], 200);
    }

    public function generateQr($id)
    {
        $frontendUrl = env('FRONTEND_URL');
        if (!$frontendUrl) {
            return response()->json(['message' => 'Error de configuración.'], 500);
        }

        $url_destino = $frontendUrl . "/asistencia/" . $id;

        try {
            $qr = QrCode::size(300)->generate($url_destino);
            return response()->json([
                'qr_code' => (string) $qr,
                'url_destino' => $url_destino
            ]);
        } catch (\Exception $e) {
            Log::error('Error al generar el QR: ' . $e->getMessage());
            return response()->json(['message' => 'Error interno al generar el código QR.'], 500);
        }
    }
}