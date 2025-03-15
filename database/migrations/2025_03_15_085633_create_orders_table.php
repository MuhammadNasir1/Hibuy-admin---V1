<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->integer('user_id'); // Reference to users table
            $table->string('tracking_id')->unique(); // Unique tracking ID
            $table->longText('order_items'); // Change from JSON to LONGTEXT
            $table->decimal('total');
            $table->decimal('delivery_fee')->nullable();
            $table->decimal('grand_total');
            $table->string('customer_name');
            $table->string('phone', 20);
            $table->text('address');
            $table->string('second_phone', 20)->nullable();
            $table->string('status')->default('pending');
            $table->string('order_date'); // Store formatted date (e.g., "Jan 2, 2024")
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
