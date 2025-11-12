<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('alumno', function (Blueprint $table) {
            $table->string('NUM_CONTROL', 30)->primary();   
            $table->string('NOMBRE', 250);
            $table->string('CORREO_INSTITUCIONAL', 200)->unique();
            $table->unsignedBigInteger('ID_GRUPO')->nullable();
            $table->foreign('ID_GRUPO')
                  ->references('ID_GRUPO')->on('grupo')     
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumno');
    }
};