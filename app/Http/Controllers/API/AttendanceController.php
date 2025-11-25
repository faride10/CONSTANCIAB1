<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Alumno;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AttendanceVerificationMail;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceController extends Controller
{
    public function requestAttendance(Request $request)
    {
        $request->validate([
            'control_number' => 'required|string',
            'conference_id' => 'required|exists:CONFERENCIA,ID_CONFERENCIA',
        ]);

        $alumno = Alumno::where('NUM_CONTROL', $request->control_number)->first();

        if (!$alumno) {
            return response()->json(['message' => 'Número de control no encontrado.'], 404);
        }

        $existingAttendance = Asistencia::where('ID_CONFERENCIA', $request->conference_id)
                                ->where('NUM_CONTROL', $request->control_number)
                                ->first();

        if ($existingAttendance && $existingAttendance->STATUS === 'confirmed') {
            return response()->json(['message' => 'Ya has registrado tu asistencia previamente. ✅'], 400);
        }

        Asistencia::updateOrCreate(
            [
                'ID_CONFERENCIA' => $request->conference_id,
                'NUM_CONTROL' => $request->control_number
            ],
            [
                'VERIFICATION_TOKEN' => null, 
                'TOKEN_EXPIRES_AT' => null,   
                'STATUS' => 'confirmed',    
                'FECHA_REGISTRO' => now()
            ]
        );

        return response()->json(['message' => '¡Tu asistencia ha sido registrada correctamente! ✅'], 200);
    }

    public function confirmAttendance($token)
    {
        return response()->json(['message' => 'La confirmación por correo está desactivada.'], 404);
    }

     public function generateQr($id)
    {
        // 🚨 CAMBIO CLAVE: Usamos la variable de entorno FRONTEND_URL en lugar de la IP local
        $frontendUrl = env('FRONTEND_URL');

        // Verificación de seguridad en caso de que la variable no esté definida en el .env
        if (!$frontendUrl) {
            Log::error('FRONTEND_URL no está definida en el archivo .env. No se puede generar el QR.');
            return response()->json(['message' => 'Error de configuración del servidor. FRONTEND_URL no definida.'], 500);
        }

        // Construimos la URL de destino usando la variable de entorno (ej. HTTP_SERVER_ADDRESS/asistencia/1)
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