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
    Schema::table('payments', function (Blueprint $table) {
        $table->unsignedBigInteger('course_type_id')->nullable()->after('payment_type');
        $table->unsignedBigInteger('subscription_type_id')->nullable()->after('course_type_id');
        $table->enum('gender', ['Homme', 'Femme', 'Mixte'])->nullable()->after('subscription_type_id');
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropColumn(['course_type_id', 'subscription_type_id', 'gender']);
    });
}

};
