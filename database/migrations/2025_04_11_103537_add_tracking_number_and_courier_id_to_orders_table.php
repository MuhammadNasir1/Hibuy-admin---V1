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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number')->nullable(); // Add the tracking number column
            $table->unsignedBigInteger('courier_id')->nullable(); // Add the courier ID column
            $table->foreign('courier_id')->references('courier_id')->on('couriers')->onDelete('set null'); // Reference the couriers table
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['courier_id']);
            $table->dropColumn('tracking_number');
            $table->dropColumn('courier_id');
        });
    }
};
