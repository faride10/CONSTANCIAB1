<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
   public function up(): void {
    Schema::create('VERIFICACION_TEMPORAL', function (Blueprint $table) {
        $table->string('NUM_CONTROL_FK', 30)->primary();
        $table->foreign('NUM_CONTROL_FK')->references('NUM_CONTROL')->on('ALUMNOS')->onDelete('cascade');
        $table->string('CODIGO_OTP', 10);
        $table->timestamp('EXPIRA_EN');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('verificacion_temporal');
    }
};
