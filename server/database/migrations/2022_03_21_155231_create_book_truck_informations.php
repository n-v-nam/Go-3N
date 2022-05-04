<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookTruckInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_truck_informations', function (Blueprint $table) {
            $table->id('book_truck_information_id');
            $table->integer('customer_id');
            $table->tinyInteger('from_city_id');
            $table->tinyInteger('to_city_id');
            $table->integer('item_type_id');
            $table->integer('category_truck_id')->nullable();
            $table->float('weight_product');
            $table->float('price')->nullable();
            $table->float('width')->nullable();
            $table->float('length')->nullable();
            $table->float('height')->nullable();
            $table->integer('count_product')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('book_truck_informations');
    }
}
