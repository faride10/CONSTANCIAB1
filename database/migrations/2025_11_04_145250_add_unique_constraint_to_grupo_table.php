<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('grupo', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_DOCENTE')->nullable()->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('grupo', function (Blueprint $table) {
            $table->dropUnique('grupo_id_docente_unique');
            $table->unsignedBigInteger('ID_DOCENTE')->nullable()->change();
        });
    }
};