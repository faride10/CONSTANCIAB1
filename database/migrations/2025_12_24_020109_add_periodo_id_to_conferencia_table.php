<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
{
    Schema::table('conferencia', function (Blueprint $table) {
        $table->foreignId('periodo_id')
              ->nullable()  
              ->constrained('periodo_academicos')
              ->onDelete('set null');   
    });
}

   public function down(): void
{
    Schema::table('conferencia', function (Blueprint $table) {
        $table->dropForeign(['periodo_id']);
        $table->dropColumn('periodo_id');
    });
}
};
