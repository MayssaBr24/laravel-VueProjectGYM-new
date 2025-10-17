<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('reservations')) {
            Schema::table('reservations', function (Blueprint $table) {

                // Ajouter payment_id si elle n'existe pas
                if (!Schema::hasColumn('reservations', 'payment_id')) {
                    $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
                }

                // Ajouter coach_id si elle n'existe pas
                if (!Schema::hasColumn('reservations', 'coach_id')) {
                    $table->foreignId('coach_id')->constrained('coaches'); // ou ->nullable() si pas obligatoire
                }

                // Ajouter l'index unique sur user_id et course_id
                try {
                    $table->unique(['user_id', 'course_id']);
                } catch (\Exception $e) {
                    // ignore si l'index existe déjà
                }
            });
        }
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {

            if (Schema::hasColumn('reservations', 'payment_id')) {
                $table->dropForeign(['payment_id']);
                $table->dropColumn('payment_id');
            }

            if (Schema::hasColumn('reservations', 'coach_id')) {
                $table->dropForeign(['coach_id']);
                $table->dropColumn('coach_id');
            }

            // Supprimer l'index unique si il existe
            try {
                $table->dropUnique(['user_id', 'course_id']);
            } catch (\Exception $e) {
                // ignore si l'index n'existe pas
            }
        });
    }
};
