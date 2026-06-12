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
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cinema_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', [
                'regular',
                '3d',
                'imax',
                '4dx',
                'vip',
                'premiere'
            ])->default('regular');
            $table->integer('rows'); // jumlah baris (A - Z)
            $table->integer('cols'); // jumlah kolom (1-20)
            $table->integer('total_seats')->default(0);
            $table->json('facilities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
