<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_informations', function (Blueprint $table) {
            $table->id('order_information_id');
            $table->string('code_order');
            $table->integer('book_truck_information_id');
            $table->integer('post_id');
            $table->timestamp('recieve_at')->nullable();
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('order_informations');
    }
}
