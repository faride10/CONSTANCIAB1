<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void {
    Schema::create('GRUPO', function (Blueprint $table) {
        $table->id('ID_GRUPO');
        $table->string('NOMBRE', 150);
        $table->string('CARRERA', 150)->nullable();
        $table->foreignId('ID_DOCENTE')->nullable()->unique()->constrained('DOCENTE', 'ID_DOCENTE')->onDelete('set null');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('grupo');
    }
};
