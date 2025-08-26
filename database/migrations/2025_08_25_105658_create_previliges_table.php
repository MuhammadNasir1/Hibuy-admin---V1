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
        Schema::create('previliges', function (Blueprint $table) {
            $table->id('previlige_id'); // Primary Key
            $table->unsignedBigInteger('user_id'); // Role reference
            $table->unsignedBigInteger('menu_id'); // Menu reference

            // Permission columns
            $table->boolean('can_view')->default(0);
            $table->boolean('can_add')->default(0);
            $table->boolean('can_edit')->default(0);
            $table->boolean('can_delete')->default(0);

            $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('previliges');
    }
};

