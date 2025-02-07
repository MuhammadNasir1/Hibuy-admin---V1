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
        Schema::create('seller', function (Blueprint $table) {
            $table->id('seller_id');
            $table->integer('user_id');
            $table->string('seller_type')->nullable();
            $table->longText('personal_info')->nullable();
            $table->longText('store_info')->nullable();
            $table->longText('documents_info')->nullable();
            $table->longText('bank_info')->nullable();
            $table->longText('business_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller');
    }
};
