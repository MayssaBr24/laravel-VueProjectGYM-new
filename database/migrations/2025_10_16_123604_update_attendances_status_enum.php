<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // ⚠️ On change le type de colonne (nécessite doctrine/dbal)
            $table->enum('status', ['reserved', 'present', 'absent', 'intrus', 'unreserved'])
                  ->default('unreserved')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status', ['reserved', 'unreserved', 'intrus'])
                  ->default('unreserved')
                  ->change();
        });
    }
};
