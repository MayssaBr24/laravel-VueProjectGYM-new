<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('courses', function (Blueprint $table) {
        $table->string('type')->nullable(); // ✅ type de cours
        $table->decimal('price', 8, 2)->default(0); // ✅ prix du cours
    });
}

public function down()
{
    Schema::table('courses', function (Blueprint $table) {
        $table->dropColumn(['type', 'price']);
    });
}


   
};
