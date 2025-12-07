<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conferencia;
use App\Models\Grupo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Throwable;


class QrCodeController extends Controller
{
    public function generate(Conferencia $conferencia, Grupo $grupo)
    {
        Log::info("Solicitud de QR para Conferencia ID: {$conferencia->id_conferencia}, Grupo ID: {$grupo->id_grupo}");

        if (!$grupo) {
            return response()->json(['message' => 'Grupo no encontrado.'], 404);
        }

        $qrCodeInternalData = [
            'conference_id' => $conferencia->id_conferencia,
            'group_id'      => $grupo->id_grupo,
        ];

        $qrDataString = json_encode($qrCodeInternalData);
        $formattedDate = 'N/A';

        try {
            if ($conferencia->fecha_hora) {
                $formattedDate = Carbon::parse($conferencia->fecha_hora)->format('d-m-Y H:i');
            }
        } catch (\Exception $e) {
            Log::error("Error al formatear fecha_hora: " . $e->getMessage());
        }

        $displayInfo = [
            'conference_name' => $conferencia->nombre_conferencia ?? 'Conferencia Sin Nombre',
            'group_name'      => $grupo->nombre ?? 'Grupo Sin Nombre',
            'date'            => $formattedDate,
        ];

        try {

    $qrPng = QrCode::format('svg') 
    ->size(300)
    ->margin(10)
    ->errorCorrection('H')
    ->generate($qrDataString);

    $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrPng);

} catch (\Throwable $e) {

    \Log::error("Error FATAL al generar QR:");
    \Log::error($e);

    return response()->json(['message' => 'Error interno al generar QR'], 500);
    
}

        return response()->json([
            'qrCodeBase64' => $qrCodeBase64,
            'displayInfo'   => $displayInfo,
        ]);
    }
}
