<?php

namespace App\Http\Controllers\API; 

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'USERNAME' => 'required|string',
            'password' => 'required|string'
        ]);

        $usuario = Usuario::where('USERNAME', $request->USERNAME)->first();

        if (! $usuario || ! Hash::check($request->password, $usuario->PASSWORD_HASH)) {
            throw ValidationException::withMessages([
                'message' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'usuario' => $usuario,
            'needs_password_change' => $usuario->needs_password_change
        ]);
    } 

    public function changePassword(Request $request)
    {
        Log::info('--- Iniciando changePassword ---');

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $usuario = $request->user();
        if (!$usuario) {
             Log::error('Error en changePassword: No se pudo obtener el usuario autenticado.');
             return response()->json(['message' => 'Error de autenticación.'], 401);
        }
        Log::info('Usuario obtenido con ID: ' . $usuario->ID_USUARIO); 

        $usuario->PASSWORD_HASH = Hash::make($request->password);
        $usuario->needs_password_change = false;

        Log::info('Valores antes de guardar - Hash: (no mostrado), NeedsChange: ' . ($usuario->needs_password_change ? 'true' : 'false')); 

        try {
            $saved = $usuario->save(); 

            if ($saved) {
                Log::info('¡Guardado exitoso! NeedsChange ahora debería ser false en BD.'); 
                 return response()->json([
                    'message' => 'Contraseña actualizada exitosamente.'
                ]);
            } else {
                Log::error('Fallo al guardar (save devolvió false) para ID: ' . $usuario->ID_USUARIO); 
                 return response()->json(['message' => 'Error interno al guardar los cambios.'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Excepción al guardar en changePassword para ID ' . $usuario->ID_USUARIO . ': ' . $e->getMessage()); 
             return response()->json(['message' => 'Error interno del servidor al guardar.'], 500);
        } finally {
            Log::info('--- Finalizando changePassword ---'); 
        }
    } 

} 