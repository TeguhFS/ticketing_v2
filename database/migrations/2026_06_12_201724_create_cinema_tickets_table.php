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
        Schema::create('cinema_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cinema_order_item_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ticket_code')->unique();
            $table->string('qr_code')->nullable();
            // Snapshot data saat tiket dibuat
            $table->string('movie_title');
            $table->string('cinema_name');
            $table->string('studio_name');
            $table->string('seat_number');
            $table->string('seat_type');
            $table->string('ticket_type');
            $table->decimal('price', 10, 2);
            $table->dateTime('show_time');
            $table->string('format');
            $table->string('language');
            $table->string('holder_name');
            $table->string('holder_email');
            $table->enum('status', [
                'active',
                'used',
                'cancelled',
                'expired',
            ])->default('active');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('ticket_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cinema_tickets');
    }
};
