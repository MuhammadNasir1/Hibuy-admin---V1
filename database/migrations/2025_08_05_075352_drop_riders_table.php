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
        Schema::dropIfExists('riders');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::create('riders', function (Blueprint $table) {
            $table->id('rider_id');
            $table->string('rider_name');
            $table->string('rider_email')->unique();
            $table->string('phone')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('city')->default('faisalabad');
            $table->timestamps();
        });
    }
};
