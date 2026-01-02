<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); 

        $idFiltro = ($user->rol === 'Administrador') ? 1 : $user->id;

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $idFiltro) 
            ->orderBy('created_at', 'desc')
            ->get();

        $formatted = $notifications->map(function ($n) {
            $data = json_decode($n->data, true);
            
            return [
                'id' => $n->id,
                'titulo' => $data['titulo'] ?? 'Aviso del Sistema',
                'mensaje' => $data['mensaje'] ?? '',
                'tipo' => $data['tipo'] ?? 'INFO',
                'tiempo' => Carbon::parse($n->created_at)->diffForHumans(),
                'leido' => !is_null($n->read_at)
            ];
        });

        return response()->json($formatted);
    }

    public function clear(Request $request)
    {
        $user = $request->user();
        $idFiltro = ($user->rol === 'Administrador') ? 1 : $user->id;

        DB::table('notifications')
            ->where('notifiable_id', $idFiltro)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Notificaciones eliminadas correctamente'
        ]);
    }
}