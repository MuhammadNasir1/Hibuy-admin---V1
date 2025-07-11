<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('dashboardbanners', function (Blueprint $table) {
            $table->id('banner_id');
            $table->string('banner_title')->nullable();
            $table->string('banner_image'); // store image filename or full path
            $table->string('banner_link')->nullable(); // clickable link (optional)
            $table->boolean('banner_status')->default(0); // true=active, false=inactive
            $table->integer('banner_sort_order')->default(0); // for ordering dashboardbanners
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboardbanners');
    }
};
