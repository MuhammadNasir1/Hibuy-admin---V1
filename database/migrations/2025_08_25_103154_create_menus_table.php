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
        Schema::create('menus', function (Blueprint $table) {
            $table->id('menu_id'); // Primary key
            $table->string('menu_name'); // Menu name
            $table->string('menu_slug')->unique(); // Slug for SEO-friendly URL
            $table->text('menu_description')->nullable(); // Optional description
            $table->integer('parent_id')->nullable(); // For nested menus
            $table->boolean('status')->default(1); // 1 = Active, 0 = Inactive

            // Permission columns
            $table->boolean('can_view')->default(0);   // 1 = Yes, 0 = No
            $table->boolean('can_add')->default(0);
            $table->boolean('can_edit')->default(0);
            $table->boolean('can_delete')->default(0);

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

