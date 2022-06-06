<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_comment', function (Blueprint $table) {
            $table->id("customer_comment_id");
            $table->bigInteger("customer_id");
            $table->bigInteger("driver_id");
            $table->string("content")->default(null);
            $table->tinyInteger("rate")->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_comment');
    }
}
