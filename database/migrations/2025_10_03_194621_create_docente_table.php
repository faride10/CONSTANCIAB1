<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('docente', function (Blueprint $table) {
            $table->id('id_docente');
            $table->string('nombre', 200);
            $table->string('rfc', 13)->unique()->nullable(); 
            $table->string('correo', 200)->unique()->nullable();
            $table->string('telefono', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente');
    }
};
