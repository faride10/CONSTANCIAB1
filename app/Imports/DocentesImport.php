<?php

namespace App\Imports;

use App\Models\Docente;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; 
use Maatwebsite\Excel\Concerns\WithValidation;  
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;  
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnError;        
use Throwable;

class DocentesImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    SkipsOnFailure, 
    SkipsOnError
{
    use Importable;

    public function model(array $row)
    {
       
        $docente = Docente::create([
            'NOMBRE'   => $row['nombre_completo'],
            'RFC'      => $row['rfc'],
        ]);

        if ($docente) {
            Usuario::create([
                'USERNAME'        => $row['rfc'],   
                'PASSWORD_HASH'   => Hash::make($row['rfc']),   
                'ID_ROL'          => 2,     
                'needs_password_change' => true,    
                'ID_DOCENTE'      => $docente->ID_DOCENTE,  
            ]);
        }
        
        return $docente;
    }

    public function rules(): array
    {
        return [
            'nombre_completo' => 'required|string|max:200',
            'rfc' => 'required|string|max:13|unique:docente,RFC',   
        ];
    }    
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::error("Error de validación en fila " . $failure->row() . ": " . $failure->errors()[0]);
        }
    }

    public function onError(Throwable $e)
    {
        Log::error("Error al importar fila: " . $e->getMessage());
    }
}