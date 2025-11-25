<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id('id_asistencia');
            
            // LÍNEA CORREGIDA: Cambiado 'id_conferencias' a 'id_conferencia'
            $table->foreignId('id_conferencia')->constrained('conferencia', 'id_conferencia')->onDelete('cascade');
            
            $table->string('num_control', 30);
            $table->foreign('num_control')->references('num_control')->on('alumnos')->onDelete('cascade');
            
            $table->timestamp('fecha_registro')->useCurrent();

            $table->string('verification_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->enum('status', ['pending', 'confirmed'])->default('pending');
            
            $table->timestamps();

            $table->unique(['id_conferencia', 'num_control']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencia');
    }
};