<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ponente;
use Illuminate\Http\Request;

class PonenteController extends Controller
{
    public function index()
    {
        return response()->json(Ponente::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'NOMBRE' => 'required|string|max:200',
            'TITULO' => 'nullable|string|max:150',
            'CARGO' => 'nullable|string|max:150',
            'EMPRESA' => 'nullable|string|max:200',
            'CORREO' => 'nullable|email|max:200',
        ]);

        $ponente = Ponente::create($request->all());

        return response()->json($ponente, 201);
    }


    public function show(Ponente $ponente)
    {
      
        return response()->json($ponente->load('conferencias'));
    }

    public function update(Request $request, Ponente $ponente)
    {
        $request->validate([
            'NOMBRE' => 'sometimes|required|string|max:200',
            'TITULO' => 'sometimes|nullable|string|max:150',
            'CARGO' => 'sometimes|nullable|string|max:150',
            'EMPRESA' => 'sometimes|nullable|string|max:200',
            'CORREO' => 'sometimes|nullable|email|max:200',
        ]);

        $ponente->update($request->all());

        return response()->json($ponente);
    }

    public function destroy(Ponente $ponente)
    {
        $ponente->delete();

        return response()->json(null, 204);
    }
}