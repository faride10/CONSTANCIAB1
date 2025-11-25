<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('ASISTENCIA', function (Blueprint $table) {
            $table->id('ID_ASISTENCIA');
            
            $table->foreignId('ID_CONFERENCIA')->constrained('CONFERENCIA', 'ID_CONFERENCIA')->onDelete('cascade');
            $table->string('NUM_CONTROL', 30);
            $table->foreign('NUM_CONTROL')->references('NUM_CONTROL')->on('ALUMNOS')->onDelete('cascade');
            
            $table->timestamp('FECHA_REGISTRO')->useCurrent();

            $table->string('VERIFICATION_TOKEN')->nullable();
            $table->timestamp('TOKEN_EXPIRES_AT')->nullable();
            $table->enum('STATUS', ['pending', 'confirmed'])->default('pending');
            
            $table->timestamps();

            $table->unique(['ID_CONFERENCIA', 'NUM_CONTROL']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ASISTENCIA');
    }
};