<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PerfilController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $usuario = $request->user();

        $request->validate([
            'username' => 'required|string|max:255|unique:usuario,username,' . $usuario->id_usuario . ',id_usuario',
        ]);

        $usuario->update([
            'username' => $request->username
        ]);

        return response()->json([
            'message' => 'Perfil actualizado correctamente.',
            'usuario' => $usuario
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', 
        ]);

        $usuario = $request->user();

        if (!Hash::check($request->current_password, $usuario->password_hash)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual es incorrecta.'],
            ]);
        }

        $usuario->password_hash = Hash::make($request->new_password);
        $usuario->save();

        return response()->json(['message' => 'Contraseña actualizada con éxito.']);
    }
}