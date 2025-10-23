<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void {
    Schema::create('DOCENTE', function (Blueprint $table) {
        $table->id('ID_DOCENTE');
        $table->string('NOMBRE', 200);
        $table->string('CORREO', 200)->unique()->nullable();
        $table->string('TELEFONO', 30)->nullable();
        $table->timestamps(); // Esto crea CREATED_AT y UPDATED_AT
    });
}

    public function down(): void
    {
        Schema::dropIfExists('docente');
    }
};
