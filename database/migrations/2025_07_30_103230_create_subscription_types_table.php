<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/[timestamp]_create_subscription_types_table.php
public function up()
{
    Schema::create('subscription_types', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Homme, Femme, Mixte
        $table->decimal('price', 8, 2);
        $table->integer('duration_days');
        $table->text('description')->nullable();
        $table->timestamps();
    });
}
};
