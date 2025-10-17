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
    Schema::table('subscriptions', function (Blueprint $table) {
        $table->unsignedBigInteger('subscription_type_id')->after('user_id');

        // Optionnel si tu veux la clé étrangère
        $table->foreign('subscription_type_id')->references('id')->on('subscription_types')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
        });
    }
};
