<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void {
    Schema::create('ALUMNOS', function (Blueprint $table) {
        $table->string('NUM_CONTROL', 30)->primary();
        $table->string('NOMBRE', 250);
        $table->string('CORREO_INSTITUCIONAL', 200)->unique();
        $table->foreignId('ID_GRUPO')->nullable()->constrained('GRUPO', 'ID_GRUPO')->onDelete('set null');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
