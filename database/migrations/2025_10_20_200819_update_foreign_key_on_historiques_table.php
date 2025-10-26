<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('historiques', function (Blueprint $table) {
            // Supprimer l'ancienne contrainte s'il y en a une
            $table->dropForeign(['course_id']);

            // S'assurer que course_id est nullable
            $table->unsignedBigInteger('course_id')->nullable()->change();

            // Ajouter une nouvelle contrainte avec SET NULL
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historiques', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade'); // retour au comportement initial
        });
    }
};
