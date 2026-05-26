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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->string('avatar')->nullable()->after('address');
            $table->enum('gender', ['male', 'female'])->nullable()->after('avatar');
            $table->date('birth_date')->nullable()->after('gender');
            $table->string('id_card_number', 20)->nullable()->after('birth_date'); // NIK
            $table->string('id_card_image')->nullable()->after('id_card_number');
            $table->boolean('is_active')->default(true)->after('id_card_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'address',
                'avatar',
                'gender',
                'birth_date',
                'id_card_number',
                'id_card_image',
                'is_active'
            ]);
        });
    }
};
