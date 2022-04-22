<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->id('post_id');
            $table->integer('truck_id');
            $table->string('title');
            $table->string('content')->nullable();
            $table->tinyInteger('from_city_id');
            $table->tinyInteger('to_city_id');
            $table->boolean('post_type')->default(0);
            $table->float('weight_product')->nullable();
            $table->float('lowest_price')->nullable();
            $table->float('highest_price')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_approve')->default(0);
            $table->boolean('is_approve_at')->default(0);
            $table->integer('user_id');
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
        Schema::dropIfExists('post');
    }
}
