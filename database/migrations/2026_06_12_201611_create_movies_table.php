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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->integer('tmdb_id')->nullable()->unique(); // TMDb API ID
            $table->string('title');
            $table->string('original_title')->nullable();
            $table->string('slug')->unique();
            $table->text('synopsis')->nullable();
            $table->string('poster')->nullable();
            $table->string('backdrop')->nullable();
            $table->string('trailer_url')->nullable(); // YouTube URL
            $table->json('genres')->nullable();
            $table->integer('duration')->nullable(); // menit
            $table->enum('age_rating', [
                'SU',
                '13+',
                '17+',
                '21+'
            ])->default('SU');
            $table->string('language')->default('id');
            $table->string('director')->nullable();
            $table->json('cast')->nullable(); // Actor
            $table->json('production_companies')->nullable();
            $table->date('release_date')->nullable();
            $table->decimal('vote_average', 3, 1)->nullable(); // Rating TMDb
            $table->integer('vote_count')->nullable();
            $table->enum('status', [
                'coming_soon',
                'now_showing',
                'ended'
            ])->default('coming_soon');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('release_date');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
