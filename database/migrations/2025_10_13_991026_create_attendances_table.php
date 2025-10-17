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
       Schema::create('attendances', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('course_id')->constrained()->cascadeOnDelete();

    // Coach sans clé étrangère pour éviter l'erreur
    $table->unsignedBigInteger('coach_id')->nullable();

    $table->enum('status', ['reserved', 'unreserved', 'intrus'])->default('unreserved');
    $table->timestamp('checked_in_at')->nullable();
    $table->timestamp('attended_at')->nullable();
    $table->boolean('is_verified')->default(false);
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
