<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id('inquiry_id');
            $table->integer('buyer_id');
            $table->integer('seller_id');
            $table->integer('product_id');
            $table->integer('product_category');
            $table->integer('product_stock')->default(0);
            $table->float('amount', 10, 2);
            $table->float('twenty_percent_amount', 10, 2);
            $table->float('remaining_amount', 10, 2);
            $table->string('payment_ss')->nullable();
            $table->date('inquiry_date');
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
