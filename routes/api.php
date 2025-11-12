<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ConferenceController;
use App\Http\Controllers\API\PonenteController;
use App\Http\Controllers\API\AlumnoController;
use App\Http\Controllers\API\GrupoController;
use App\Http\Controllers\API\DocenteController;
use App\Http\Controllers\API\RolController;
use App\Http\Controllers\API\AsistenciaController;
use App\Http\Controllers\API\ConstanciaController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\QrCodeController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ReporteController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/conferencias/{conferencia}/grupos/{grupo}/qr-data', [QrCodeController::class, 'generate']);


Route::middleware('auth:sanctum')->group(function () {
    
 Route::get('/user', function (Request $request) {
        return $request->user();
    }); 
    
Route::post('/change-password', [AuthController::class, 'changePassword']);
Route::get('/dashboard/admin/summary', [DashboardController::class, 'adminSummary']);
Route::get('dashboard/recent-activities', [DashboardController::class, 'getRecentActivities']);

Route::apiResource('conferencias', ConferenceController::class);
Route::apiResource('ponentes', PonenteController::class);
Route::get('alumnos', [AlumnoController::class, 'index']);
Route::post('alumnos', [AlumnoController::class, 'store']);
Route::get('alumnos/{numControl}', [AlumnoController::class, 'show']);
Route::put('alumnos/{numControl}', [AlumnoController::class, 'update']);
Route::delete('alumnos/{numControl}', [AlumnoController::class, 'destroy']);
Route::apiResource('grupos', GrupoController::class);
Route::apiResource('docentes', DocenteController::class);
Route::apiResource('roles', RolController::class);
Route::apiResource('asistencias', AsistenciaController::class);
Route::apiResource('constancias', ConstanciaController::class);
Route::post('/alumnos/{alumno:num_control}/generar-constancia', [ConstanciaController::class, 'generar'])->name('constancias.generar');
Route::post('alumnos/importar', [AlumnoController::class, 'importar']);
Route::get('docentes', [DocenteController::class, 'index']);
Route::post('docentes/importar', [DocenteController::class, 'importar']);
Route::get('conferencias/{id}/qr', [ConferenceController::class, 'generarQrCode']);
Route::get('docente/mi-grupo', [DocenteController::class, 'getMiGrupo']);
Route::get('admin/reporte/conferencia/{conferencia}', [ReporteController::class, 'getReportePorConferencia']);
Route::get('admin/reporte/conferencia/{conferencia}/grupo/{grupo}', [ReporteController::class, 'getReportePorAlumnos']);

}); 