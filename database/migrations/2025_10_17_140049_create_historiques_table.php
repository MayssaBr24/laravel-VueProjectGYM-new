<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // ex: "Ajout de cours", "Suppression de cours", "Réservation"
            $table->string('user_role'); // ex: coach, client
            $table->unsignedBigInteger('user_id');
            $table->text('description')->nullable();
            $table->timestamp('action_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historiques');
    }
};
