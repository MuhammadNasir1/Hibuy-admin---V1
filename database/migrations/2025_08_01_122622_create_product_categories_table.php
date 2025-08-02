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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();  // primary key (automatically named `id`)

            $table->string('name');  // category name

            $table->unsignedBigInteger('parent_id')->nullable();  // parent category id for nesting

            $table->timestamps();

            // Add foreign key to itself
            $table->foreign('parent_id')->references('id')->on('product_categories')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
