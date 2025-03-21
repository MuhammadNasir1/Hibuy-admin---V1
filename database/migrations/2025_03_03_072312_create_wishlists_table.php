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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id('wishlist_id');
            $table->integer('user_id');
            $table->integer('product_id');
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'product_id']); // Prevent duplicate wishlist items
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
