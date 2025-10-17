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
    Schema::create('notifications', function (Blueprint $table) {
        $table->uuid('id')->primary(); // ID unique pour chaque notification
        $table->string('type');        // Le type de notification (ex: App\Notifications\NewCourseNotification)
        $table->morphs('notifiable');  // Crée notifiable_type et notifiable_id (relation polymorphe)
        $table->text('data');          // Données JSON ou texte associé à la notif
        $table->timestamp('read_at')->nullable(); // Date de lecture de la notif
        $table->timestamps();          // created_at et updated_at
    });
}

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
