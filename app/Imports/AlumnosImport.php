<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\Grupo;   
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;  
use Maatwebsite\Excel\Concerns\WithValidation;  
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;  
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Throwable;
use Illuminate\Support\Facades\Log; 

class AlumnosImport implements 
    ToModel, 
    WithHeadingRow,     
    WithValidation,     
    SkipsOnFailure,     
    SkipsOnError    
{
    use Importable;

    private $grupos;

    public function __construct()
    {
      
        $this->grupos = Grupo::pluck('id_grupo', 'nombre')->all();
    }

    public function model(array $row)
    {
        
        $idGrupo = $this->grupos[$row['nombre_grupo']] ?? null;

        return new Alumno([
            'num_control'         => $row['num_control'],
            'nombre'              => $row['nombre_completo'],
            'correo_institucional'=> $row['correo_institucional'],
            'id_grupo'            => $idGrupo,
        ]);
    }

    public function rules(): array
    {
        return [
            'num_control' => 'required|max:30|unique:alumno,num_control',
            'nombre_completo' => 'required|string|max:250',
            'correo_institucional' => 'required|email|max:200|unique:alumno,correo_institucional',
            'nombre_grupo' => 'nullable|string|exists:grupo,nombre',
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