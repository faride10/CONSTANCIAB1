<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        // Modifica la tabla 'asistencia'
        Schema::table('asistencia', function (Blueprint $table) {
            
            // Añade la clave foránea 'id_grupo'
            $table->foreignId('id_grupo')
                  ->nullable()
                  ->constrained('grupo', 'id_grupo') // Referencia a la tabla 'grupo'
                  ->after('num_control');
        });
    }

    public function down(): void
    {
        // Revierte los cambios en la tabla 'asistencia'
        Schema::table('asistencia', function (Blueprint $table) {
            $table->dropForeign(['id_grupo']);
            $table->dropColumn('id_grupo');
        });
    }
};