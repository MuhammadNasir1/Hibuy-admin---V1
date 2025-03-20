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
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('is_boosted')->default(0)->after('product_status');
            $table->string('boost_start_date')->nullable()->after('is_boosted');
            $table->string('boost_end_date')->nullable()->after('boost_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_boosted', 'boost_start_date', 'boost_end_date']);
        });
    }
};
