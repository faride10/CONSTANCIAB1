<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conferencia;
use App\Models\Grupo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Throwable;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;

class QrCodeController extends Controller
{
    public function generate(Conferencia $conferencia, Grupo $grupo)
    {
        Log::info("Solicitud de QR para Conferencia ID: {$conferencia->ID_CONFERENCIA}, Grupo ID: {$grupo->ID_GRUPO}");

        if (!$grupo) {
            return response()->json(['message' => 'Grupo no encontrado.'], 404);
        }

        $qrCodeInternalData = [
            'conference_id' => $conferencia->ID_CONFERENCIA,
            'group_id'      => $grupo->ID_GRUPO,
        ];

        $qrDataString = json_encode($qrCodeInternalData);
        $formattedDate = 'N/A';

        try {
            if ($conferencia->FECHA_HORA) {
                $formattedDate = Carbon::parse($conferencia->FECHA_HORA)->format('d-m-Y H:i');
            }
        } catch (\Exception $e) {
            Log::error("Error al formatear FECHA_HORA: " . $e->getMessage());
        }

        $displayInfo = [
            'conference_name' => $conferencia->NOMBRE_CONFERENCIA ?? 'Conferencia Sin Nombre',
            'group_name'      => $grupo->NOMBRE ?? 'Grupo Sin Nombre',
            'date'            => $formattedDate,
        ];

        try {

    $qrPng = QrCode::format('png')
        ->size(300)
        ->margin(10)
        ->errorCorrection('H')
        ->generate($qrDataString);

    $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrPng);

} catch (\Throwable $e) {

    \Log::error("Error FATAL al generar QR:");
    \Log::error($e);

    return response()->json(['message' => 'Error interno al generar QR'], 500);
    
}

        return response()->json([
            'qrCodeBased64' => $qrCodeBase64,
            'displayInfo'   => $displayInfo,
        ]);
    }
}
