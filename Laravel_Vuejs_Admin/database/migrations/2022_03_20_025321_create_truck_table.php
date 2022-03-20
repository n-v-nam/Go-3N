<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTruckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truck', function (Blueprint $table) {
            $table->id('truck_id');
            $table->string('license_plates');
            $table->integer('customer_id');
            $table->integer('category_truck_id');
            $table->string('name')->nullable();
            $table->float('width')->nullable();
            $table->float('length')->nullable();
            $table->float('height')->nullable();
            $table->float('weight');
            $table->float('weight_items');
            $table->integer('count_order')->default(0);
            $table->integer('location_now_city_id')->nullable();
            $table->timestamp('location_now_at')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('user_id_accept')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('truck');
    }
}
