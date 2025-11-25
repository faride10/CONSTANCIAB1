<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        // Creación de la tabla 'constancia'
        Schema::create('constancia', function (Blueprint $table) {
            // Clave primaria
            $table->id('id_constancia');
            
            // Columna 'num_control' (debe ser única y es una clave foránea)
            $table->string('num_control', 30)->unique();
            
            // Definición de la clave foránea a la tabla 'alumnos'
            $table->foreign('num_control')
                  ->references('num_control')
                  ->on('alumnos')
                  ->onDelete('cascade');
                  
            // Fecha de emisión, usa la fecha actual por defecto
            $table->date('fecha_emision')->useCurrent();
            
            // Opcionalmente, puedes añadir timestamps si necesitas created_at y updated_at
            // $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('constancia');
    }
};