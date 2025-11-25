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
use App\Http\Controllers\Api\AttendanceController;


// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::get('/conferencias/{conferencia}/grupos/{grupo}/qr-data', [QrCodeController::class, 'generate']);
Route::post('/attendance/request', [AttendanceController::class, 'requestAttendance']);
 Route::get('/attendance/confirm/{token}', [AttendanceController::class, 'confirmAttendance']);
 Route::get('/public/conferencia/{id}', [ConferenceController::class, 'getPublicInfo']);

// Rutas protegidas (requieren Token)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    }); 
    
    Route::get('grupo/{id}/docente', [App\Http\Controllers\API\GrupoController::class, 'getDocenteByGroupId']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/dashboard/admin/summary', [DashboardController::class, 'adminSummary']);
    Route::get('dashboard/recent-activities', [DashboardController::class, 'getRecentActivities']);

    Route::get('asistencias/conferencia/{id}', [AsistenciaController::class, 'porConferencia']);
    Route::get('conferencias/{id}/asistencias', [AsistenciaController::class, 'porConferencia']);
 

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
    
    Route::get('docente/dashboard-summary', [DocenteController::class, 'getDashboardSummary']);     
    Route::post('/alumnos/{alumno:num_control}/generar-constancia', [ConstanciaController::class, 'generar'])->name('constancias.generar');
    Route::post('alumnos/importar', [AlumnoController::class, 'importar']);
    
    Route::get('docentes', [DocenteController::class, 'index']);
    Route::post('docentes/importar', [DocenteController::class, 'importar']);
    
    Route::get('docente/mi-grupo', [DocenteController::class, 'getMiGrupo']);
    
    Route::get('docente/perfil-data', [DocenteController::class, 'getPerfil']);
    Route::put('docente/perfil-update', [DocenteController::class, 'updatePerfil']);
    Route::put('docente/password-update', [DocenteController::class, 'updatePassword']);

    Route::get('admin/reporte/conferencia/{conferencia}', [ReporteController::class, 'getReportePorConferencia']);
    Route::get('admin/reporte/conferencia/{conferencia}/grupo/{grupo}', [ReporteController::class, 'getReportePorAlumnos']);
    Route::get('/conferencia/{id}/qr', [App\Http\Controllers\Api\AttendanceController::class, 'generateQr']);

    

});