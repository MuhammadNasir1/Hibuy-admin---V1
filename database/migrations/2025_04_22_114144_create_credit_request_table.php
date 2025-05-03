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
        Schema::create('credit_request', function (Blueprint $table) {
            $table->id('credit_id');
            $table->integer('user_id');
            $table->integer('amount');
            $table->integer('credit_use');
            $table->text('reason');
            $table->string('request_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_request');
    }
};
