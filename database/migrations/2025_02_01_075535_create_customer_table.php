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
        Schema::create('customer', function (Blueprint $table) {
            $table->id('customer_id');
            $table->integer('user_id');
            $table->string('customer_image')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_gender')->nullable();
            $table->date('customer_dob')->nullable();
            $table->longText('payment_method')->nullable();
            $table->longText('customer_addresses')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
