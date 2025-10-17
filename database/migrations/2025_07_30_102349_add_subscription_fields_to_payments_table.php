<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/2025_07_30_102349_add_subscription_fields_to_payments_table.php
public function up()
{
    Schema::table('payments', function (Blueprint $table) {
        // Assurez-vous que les colonnes existent déjà
        if (!Schema::hasColumn('payments', 'subscription_type_id')) {
            $table->unsignedBigInteger('subscription_type_id')->nullable();
        }
        
        if (!Schema::hasColumn('payments', 'subscription_id')) {
            $table->unsignedBigInteger('subscription_id')->nullable();
        }

        // Ajoutez les contraintes seulement après avoir créé les colonnes
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('subscription_type_id')
                  ->references('id')
                  ->on('subscription_types')
                  ->onDelete('set null');

            $table->foreign('subscription_id')
                  ->references('id')
                  ->on('subscriptions')
                  ->onDelete('set null');
        });
    });
}

public function down()
{
    Schema::table('payments', function (Blueprint $table) {
        $table->dropForeign(['subscription_type_id']);
        $table->dropForeign(['subscription_id']);
        $table->dropColumn(['subscription_type_id', 'subscription_id']);
    });
}
};
