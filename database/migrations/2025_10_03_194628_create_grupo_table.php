<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('GRUPO', function (Blueprint $table) {
            $table->id('ID_GRUPO');
            $table->string('NOMBRE', 100);

            // Un docente solo puede tener un grupo
            $table->foreignId('ID_DOCENTE')
                  ->unique()
                  ->constrained('docente', 'ID_DOCENTE')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('GRUPO');
    }
};
