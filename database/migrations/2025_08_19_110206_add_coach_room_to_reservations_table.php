<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Ajout de coach_id si pas encore présent
            if (!Schema::hasColumn('reservations', 'coach_id')) {
                $table->unsignedBigInteger('coach_id')->nullable()->after('course_id');
                $table->foreign('coach_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            }

            // Ajout de room_id si pas encore présent
            if (!Schema::hasColumn('reservations', 'room_id')) {
                $table->unsignedBigInteger('room_id')->nullable()->after('coach_id');
                // Si tu as une table rooms :
                // $table->foreign('room_id')->references('id')->on('rooms')->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'coach_id')) {
                $table->dropForeign(['coach_id']);
                $table->dropColumn('coach_id');
            }

            if (Schema::hasColumn('reservations', 'room_id')) {
                // $table->dropForeign(['room_id']); // seulement si tu ajoutes une FK
                $table->dropColumn('room_id');
            }
        });
    }
};
