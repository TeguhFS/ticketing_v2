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
        Schema::create('showtime_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('showtime_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('seat_layout_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('locked_by_user_id')
                ->nullable()
                ->constrained('users')->nullOnDelete();
            $table->enum('status', [
                'available',
                'locked', // Dikunci sementara saat user memilih kursi (10 menit)
                'booked',
                'disabled',
            ])->default('available');
            $table->timestamp('locked_until')->nullable();
            $table->timestamps();

            // Setiap kursi perjadwal harus unik
            $table->unique(['showtime_id', 'seat_layout_id'], 'unique_showtime_seat');

            $table->index(['showtime_id', 'status']);
            $table->index(['status', 'locked_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtime_seats');
    }
};
