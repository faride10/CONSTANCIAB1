<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index()
    {
        return Rol::all();
    }

    public function store(Request $request)
    {
        $request->validate(['NOMBRE_ROL' => 'required|string|max:50|unique:ROL,NOMBRE_ROL']);
        $rol = Rol::create($request->all());
        return response()->json($rol, 201);
    }

    public function show(Rol $rol)
    {
        return $rol;
    }

    public function update(Request $request, Rol $rol)
    {
        $request->validate(['NOMBRE_ROL' => 'sometimes|required|string|max:50|unique:ROL,NOMBRE_ROL,' . $rol->ID_ROL . ',ID_ROL']);
        $rol->update($request->all());
        return response()->json($rol);
    }

    public function destroy(Rol $rol)
    {
        $rol->delete();
        return response()->json(null, 204);
    }
}