<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouriersTable extends Migration
{
    public function up()
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id('courier_id');
            $table->string('courier_name')->unique(); // e.g. TCS, OCS
            $table->string('courier_tracking_url')->nullable(); // optional: for online tracking
            $table->string('courier_contact_number')->nullable(); // optional: for support
            $table->boolean('is_active')->default(1); // in case you want to deactivate
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('couriers');
    }
}
