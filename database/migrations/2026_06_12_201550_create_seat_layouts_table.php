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
        Schema::create('seat_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->string('seat_number'); // A1, A2, B5, dll
            $table->string('row_label');  // A, B, C, ...
            $table->integer('col_number'); // 1, 2, 3, ...
            $table->enum('seat_type', [
                'regular',
                'vip',
                'couple',
                'disabled',
                'blocked'
            ])->default('regular');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Mencegah satu studio mempunyai duplikat data seat number
            $table->unique(['studio_id', 'seat_number']);

            $table->index(['studio_id', 'row_label', 'col_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_layouts');
    }
};
