<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                // Ajouter colonne course_type_id si elle n'existe pas
                if (!Schema::hasColumn('payments', 'course_type_id')) {
                    $table->foreignId('course_type_id')->nullable()->constrained('course_types')->nullOnDelete();
                }

                // Ajouter colonne subscription_type_id si elle n'existe pas
                if (!Schema::hasColumn('payments', 'subscription_type_id')) {
                    $table->foreignId('subscription_type_id')->nullable()->constrained('subscription_types')->nullOnDelete();
                }

                // Ajouter d'autres colonnes si nécessaire
                if (!Schema::hasColumn('payments', 'gender')) {
                    $table->string('gender')->nullable();
                }

                // Tu peux répéter ce pattern pour toutes les colonnes que tu veux ajouter
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasColumn('payments', 'course_type_id')) {
                    $table->dropForeign(['course_type_id']);
                    $table->dropColumn('course_type_id');
                }

                if (Schema::hasColumn('payments', 'subscription_type_id')) {
                    $table->dropForeign(['subscription_type_id']);
                    $table->dropColumn('subscription_type_id');
                }

                if (Schema::hasColumn('payments', 'gender')) {
                    $table->dropColumn('gender');
                }
            });
        }
    }
};
