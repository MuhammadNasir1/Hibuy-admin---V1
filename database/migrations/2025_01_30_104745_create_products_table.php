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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->integer('user_id');
            $table->integer('store_id');
            $table->string('product_name');
            $table->text('product_description')->nullable();
            $table->string('product_brand');
            $table->string('product_category');
            $table->integer('product_stock')->default(0);
            $table->float('purchase_price');
            $table->float('product_price');
            $table->float('product_discount');
            $table->float('product_discounted_price');
            $table->longText('product_images')->nullable();
            $table->longText('product_attributes')->nullable();
            $table->longText('product_variation')->nullable();
            $table->integer('product_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
