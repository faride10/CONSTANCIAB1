<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodoAcademico;

class PeriodoSeeder extends Seeder
{
    public function run(): void
    {
        PeriodoAcademico::create([
            'nombre' => 'Agosto-Diciembre 2025',
            'fecha_inicio' => '2025-08-01',
            'fecha_fin' => '2025-12-31',
            'activo' => true
        ]);

        PeriodoAcademico::create([
            'nombre' => 'Enero-Junio 2025',
            'fecha_inicio' => '2025-01-01',
            'fecha_fin' => '2025-06-30',
            'activo' => false
        ]);
    }
}