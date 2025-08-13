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
        Schema::table('riders', function (Blueprint $table) {
            $table->string('profile_picture')->after('city');
            $table->string('id_card_front')->after('profile_picture');
            $table->string('id_card_back')->after('id_card_front');
            $table->string('driving_license_front')->nullable()->after('id_card_back');
            $table->string('driving_license_back')->nullable()->after('driving_license_front');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riders', function (Blueprint $table) {
            $table->dropColumn([
                'profile_picture',
                'id_card_front',
                'id_card_back',
                'driving_license_front',
                'driving_license_back'
            ]);
        });
    }
};
