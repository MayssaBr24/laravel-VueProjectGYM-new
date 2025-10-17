<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Rendre coach_id nullable
            $table->unsignedBigInteger('coach_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Revenir à non nullable si besoin
            $table->unsignedBigInteger('coach_id')->nullable(false)->change();
        });
    }
};
