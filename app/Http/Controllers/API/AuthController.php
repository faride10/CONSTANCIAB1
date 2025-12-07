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
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('username', $request->username)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password_hash)) {
            return response()->json(['message' => 'Usuario o contraseña incorrectos.'], 401);
        }

        $usuario->load('rol', 'docente');
        $token = $usuario->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'usuario' => $usuario   
        ]);
    }

   public function changePassword(Request $request)
{
    try {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $usuario = $request->user(); 

        if (!$usuario) {
             return response()->json(['message' => 'Token de autenticación no válido.'], 401); 
        }

        $usuario->update([
            'password_hash' => \Illuminate\Support\Facades\Hash::make($request->password), 
            'needs_password_change' => 0 
        ]);

        return response()->json(['message' => 'Contraseña actualizada exitosamente.', 'redirect_to' => '/panel/docente'], 200);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['message' => 'Error de validación', 'errors' => $e->errors()], 422);
    }
}
}