<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('conferencia', function (Blueprint $table) {
        if (!Schema::hasColumn('conferencia', 'esta_archivado')) {
        $table->boolean('esta_archivado')->default(false);
     }
    });

        Schema::table('asistencia', function (Blueprint $table) {
        if (!Schema::hasColumn('asistencia', 'esta_archivado')) {
        $table->boolean('esta_archivado')->default(false);
     }
    });
    }

    public function down(): void
    {
        Schema::table('conferencia', function (Blueprint $table) {
        if (Schema::hasColumn('conferencia', 'esta_archivado')) {
        $table->dropColumn('esta_archivado');
     }
    });

        Schema::table('asistencia', function (Blueprint $table) {
        if (Schema::hasColumn('asistencia', 'esta_archivado')) {
        $table->dropColumn('esta_archivado');
     }
    });
    }
};