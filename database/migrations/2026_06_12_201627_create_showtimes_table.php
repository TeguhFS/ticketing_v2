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
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('price_regular', 10, 2)->default(0);
            $table->decimal('price_student', 10, 2)->default(0);
            $table->decimal('price_senior', 10, 2)->default(0);
            $table->decimal('price_vip', 10, 2)->default(0);
            $table->enum('language', ['dub', 'sub', 'original'])->default('sub');
            $table->enum('format', [
                '2d',
                '3d',
                'imax',
                '4dx',
                'imax_3d',
                'dolby'
            ])->default('2d');
            $table->enum('status', [
                'open',
                'full',
                'cancelled',
                'ended'
            ])->default('open');
            $table->integer('available_seats')->default(0);
            $table->integer('booked_seats')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Mencegah agar tidak ada bentrok jadwal di studio yang sama
            $table->index(['studio_id', 'start_time', 'status']);
            $table->index(['movie_id', 'start_time', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
