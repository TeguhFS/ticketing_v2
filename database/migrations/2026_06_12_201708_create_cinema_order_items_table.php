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
        Schema::create('cinema_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cinema_order_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('showtime_seat_id')
                ->constrained()->cascadeOnDelete();
            $table->enum('ticket_type', [
                'regular',
                'student',
                'senior',
                'vip'
            ])->default('regular');
            $table->decimal('price', 10, 2);
            $table->string('seat_number');
            $table->string('seat_type');
            $table->timestamps();

            $table->unique(
                ['showtime_seat_id'],
                'unique_booked_seat'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cinema_order_items');
    }
};
