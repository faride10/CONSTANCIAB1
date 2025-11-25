<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        // Modifica la tabla 'docente'
        Schema::table('docente', function (Blueprint $table) {
            
            // Añade la columna 'rfc' (string, 13 caracteres, único, nullable)
            $table->string('rfc', 13)
                  ->unique()
                  ->nullable()
                  ->after('nombre');
        });
    }

    public function down(): void
    {
        // Revierte los cambios en la tabla 'docente'
        Schema::table('docente', function (Blueprint $table) {
            
            // Elimina la columna 'rfc'
            $table->dropColumn('rfc');
        });
    }
};