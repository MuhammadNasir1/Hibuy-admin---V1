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
        Schema::create('returns', function (Blueprint $table) {
            $table->id('return_id');
            $table->unsignedBigInteger('order_id');     // Original order
            $table->unsignedBigInteger('user_id');      // Customer who made the return

            $table->json('return_items');               // JSON array of returned products

            $table->decimal('return_total', 65, 2)->default(0);       // Total before adjustments
            $table->decimal('return_delivery_fee', 65, 2)->default(0); // Refunded delivery fee, if any
            $table->decimal('return_grand_total', 65, 2)->default(0);  // Total refund amount

            $table->string('return_status')->default('pending');     // pending / approved / rejected
            $table->string('return_reason');
            $table->string('return_recieve_option')->default('pickup'); // pickup / delivery
            $table->string('return_address')->nullable(); // Address for pickup or delivery
            $table->string('return_courier')->default('TCS'); // Courier service for return delivery
            $table->text('return_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
