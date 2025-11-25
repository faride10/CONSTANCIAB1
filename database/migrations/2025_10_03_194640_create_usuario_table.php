<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('USUARIO', function (Blueprint $table) {
            $table->id('ID_USUARIO');
            $table->string('USERNAME', 100)->unique();
            $table->string('PASSWORD_HASH', 255);

            $table->foreignId('ID_ROL')
                  ->constrained('ROL', 'ID_ROL');

            // Un docente solo puede tener un usuario
            $table->foreignId('ID_DOCENTE')
                  ->nullable()
                  ->unique()
                  ->constrained('docente', 'ID_DOCENTE')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('USUARIO');
    }
};
