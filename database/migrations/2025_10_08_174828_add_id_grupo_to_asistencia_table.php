<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        
        Schema::table('ASISTENCIA', function (Blueprint $table) {
         $table->foreignId('ID_GRUPO')->nullable()->constrained('GRUPO', 'ID_GRUPO')->after('NUM_CONTROL');
            //
        });
    }

   
    public function down(): void
    {
        Schema::table('ASISTENCIA', function (Blueprint $table) {
        $table->dropForeign(['ID_GRUPO']);
        $table->dropColumn('ID_GRUPO');
            
        });
    }
};
