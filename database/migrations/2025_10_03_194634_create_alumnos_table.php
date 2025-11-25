<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('alumnos', function (Blueprint $table) {
            $table->string('num_control', 30)->primary();   
            $table->string('nombre', 250);
            $table->string('correo_institucional', 200)->unique();
            $table->unsignedBigInteger('id_grupo')->nullable();
            $table->foreign('id_grupo')
                  ->references('id_grupo')->on('grupo')     
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};