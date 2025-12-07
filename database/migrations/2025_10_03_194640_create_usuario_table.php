<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('username', 100)->unique();
            $table->string('password_hash', 255);

            $table->foreignId('id_rol')
                  ->constrained('rol', 'id_rol');

            $table->foreignId('id_docente')
                  ->nullable()
                  ->unique()
                  ->constrained('docente', 'id_docente')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
