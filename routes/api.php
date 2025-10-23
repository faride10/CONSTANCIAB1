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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

Route::get('/user', function (Request $request) {
    return $request->user();
    }); 

Route::get('/dashboard/admin/summary', [DashboardController::class, 'adminSummary']);
Route::post('/change-password', [AuthController::class, 'changePassword']);
Route::get('/conferencias/{conferencia}/grupos/{grupo}/qr-data', [QrCodeController::class, 'generate']);

Route::apiResource('conferencias', ConferenceController::class);
Route::apiResource('ponentes', PonenteController::class);
Route::apiResource('alumnos', AlumnoController::class);
Route::apiResource('grupos', GrupoController::class);
Route::apiResource('docentes', DocenteController::class);
Route::apiResource('roles', RolController::class);
Route::apiResource('asistencias', AsistenciaController::class);
Route::apiResource('constancias', ConstanciaController::class);
Route::post('/alumnos/{alumno:num_control}/generar-constancia', [ConstanciaController::class, 'generar'])->name('constancias.generar');

}); 