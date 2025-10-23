<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void {
    Schema::create('CONSTANCIA', function (Blueprint $table) {
        $table->id('ID_CONSTANCIA');
       $table->string('NUM_CONTROL', 30)->unique();
        $table->foreign('NUM_CONTROL')->references('NUM_CONTROL')->on('ALUMNOS')->onDelete('cascade');
        $table->date('FECHA_EMISION')->useCurrent();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('constancia');
    }
};
