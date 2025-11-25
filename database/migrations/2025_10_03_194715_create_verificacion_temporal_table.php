<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        // Creación de la tabla 'verificacion_temporal'
        Schema::create('verificacion_temporal', function (Blueprint $table) {
            
            // Columna principal, clave foránea y primaria a la vez
            $table->string('num_control_fk', 30)->primary();
            
            // Definición de la clave foránea a la tabla 'alumnos'
            $table->foreign('num_control_fk')
                  ->references('num_control')
                  ->on('alumnos')
                  ->onDelete('cascade');
                  
            // Código OTP (One-Time Password)
            $table->string('codigo_otp', 10);
            
            // Marca de tiempo de expiración
            $table->timestamp('expira_en');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verificacion_temporal');
    }
};