    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
      
        public function up(): void
        {
            Schema::table('DOCENTE', function (Blueprint $table) {
      
                $table->string('RFC', 13)->unique()->nullable()->after('NOMBRE');
            });
        }

        public function down(): void
        {
            Schema::table('DOCENTE', function (Blueprint $table) {
       
                $table->dropColumn('RFC');
            });
        }
    };
    
