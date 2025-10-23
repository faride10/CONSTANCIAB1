<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conferencia;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
// --- ASEGÚRATE QUE ESTAS LÍNEAS ESTÉN EXACTAMENTE ASÍ ---
use Endroid\QrCode\Builder\Builder; // Importa el Builder
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter; // Necesitamos el escritor

class QrCodeController extends Controller
{
    public function generate(Conferencia $conferencia, Grupo $grupo)
    {
        Log::info("Solicitud de QR para Conferencia ID: {$conferencia->ID_CONFERENCIA}, Grupo ID: {$grupo->ID_GRUPO}");

        if (!$grupo) {
             Log::warning("Intento de generar QR para grupo no existente: {$grupo}");
             return response()->json(['message' => 'Grupo no encontrado.'], 404);
        }

        $qrCodeInternalData = [
            'conference_id' => $conferencia->ID_CONFERENCIA,
        ];
        $qrDataString = json_encode($qrCodeInternalData);

        $formattedDate = 'N/A';
        if ($conferencia->FECHA_HORA) {
            try {
                $formattedDate = Carbon::parse($conferencia->FECHA_HORA)->format('d-m-Y H:i');
            } catch (\Exception $e) {
                Log::error("Error al formatear FECHA_HORA en QrCodeController para Conferencia ID {$conferencia->ID_CONFERENCIA}: " . $e->getMessage());
            }
        }
        $displayInfo = [
            'conference_name' => $conferencia->NOMBRE_CONFERENCIA,
            'group_name' => $grupo->NOMBRE,
            'date' => $formattedDate,
        ];

        try {
            // Código usando el Builder
            $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($qrDataString)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(ErrorCorrectionLevel::High)
                ->size(300)
                ->margin(10)
                ->build();

            $qrCodeBase64 = $result->getDataUri();

        } catch (\Exception $e) {
            Log::error("Error al generar QR para Conferencia ID {$conferencia->ID_CONFERENCIA}: " . $e->getMessage());
            return response()->json(['message' => 'Error interno al generar el código QR: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'qr_code_base64' => $qrCodeBase64,
            'display_info' => $displayInfo,
        ]);
    }
}