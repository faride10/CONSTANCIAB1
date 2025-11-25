<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('asistencia', function (Blueprint $table) {
            // Clave primaria
            $table->id('id_asistencia');
            
            // Clave foránea a la tabla 'conferencia'
            $table->foreignId('id_conferencia')
                  ->constrained('conferencia', 'id_conferencia')
                  ->onDelete('cascade');
                  
            // Clave foránea a la tabla 'alumnos'
            $table->string('num_control', 30);
            $table->foreign('num_control')
                  ->references('num_control')
                  ->on('alumnos')
                  ->onDelete('cascade');
            
            // Campos de registro
            $table->timestamp('fecha_registro')->useCurrent();

            // Campos para verificación
            $table->string('verification_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->enum('status', ['pending', 'confirmed'])->default('pending');
            
            // Timestamps automáticos de Laravel (created_at y updated_at)
            $table->timestamps();

            // Índice único para evitar duplicados
            $table->unique(['id_conferencia', 'num_control']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencia');
    }
};