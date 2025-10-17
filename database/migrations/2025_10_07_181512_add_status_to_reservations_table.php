<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reservations')) {
            Schema::table('reservations', function (Blueprint $table) {

                // ✅ Ajout du status si manquant
                if (!Schema::hasColumn('reservations', 'status')) {
                    $table->enum('status', ['pending', 'confirmed', 'present', 'cancelled'])
                          ->default('pending')
                          ->after('reservation_date');
                }

                // ✅ Ajout du checked_in_at (horodatage du scan)
                if (!Schema::hasColumn('reservations', 'checked_in_at')) {
                    $table->timestamp('checked_in_at')->nullable()->after('status');
                }

                // ✅ Ajout du coach_id
                if (!Schema::hasColumn('reservations', 'coach_id')) {
                    $table->foreignId('coach_id')->nullable()->constrained('coaches')->nullOnDelete();
                }

                // ✅ Ajout du payment_id
                if (!Schema::hasColumn('reservations', 'payment_id')) {
                    $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
                }

                // ✅ Index unique user_id + course_id
              
            });
        }
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('reservations', 'checked_in_at')) {
                $table->dropColumn('checked_in_at');
            }

            if (Schema::hasColumn('reservations', 'coach_id')) {
                $table->dropForeign(['coach_id']);
                $table->dropColumn('coach_id');
            }

            if (Schema::hasColumn('reservations', 'payment_id')) {
                $table->dropForeign(['payment_id']);
                $table->dropColumn('payment_id');
            }

            
        });
    }
};
